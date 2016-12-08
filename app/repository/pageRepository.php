<?php
require_once 'baseRepository.php';

class pageRepository extends baseRepository {
    public function getUserTags($uid) {
        return $this->table('common_tagitem')->where(['idtype'=>'uid','itemid'=>$uid])->select('tagid');
    }

    public function getPages($fid, $page, $tags, $count = 10) {
        if($page){
           $start = ($page - 1) * $count;
           return $data = $this->table('forum_thread')
                         ->ass('thread')
                         ->join(' LEFT JOIN '.$this->prefix.'common_tagitem AS tag ON tag.itemid = thread.tid AND tag.idtype = \'threadid\'')
                         ->join(' LEFT JOIN '.$this->prefix.'forum_threadclass AS type ON type.typeid = thread.typeid')
                         ->in('tag.tagid', $tags)
                         ->where(['thread.fid'=>$fid, 'thread.price'=>0, 'thread.readperm'=>0])
                         ->whereWhere('thread.typeid', '!=', 0)
                         ->order('thread.displayorder desc, thread.lastpost desc')
                         ->limit($start.' ,'.$count)
                         ->select('thread.tid, type.name, thread.author, thread.authorid, thread.subject, thread.lastpost, thread.digest, thread.highlight, thread.bgcolor');
        }else{
            return $data = $this->table('forum_thread')
                                ->ass('thread')
                                ->join(' LEFT JOIN '.$this->prefix.'common_tagitem AS tag ON tag.itemid = thread.tid AND tag.idtype = \'threadid\'')
                                ->join(' LEFT JOIN '.$this->prefix.'forum_threadclass AS type ON type.typeid = thread.typeid')
                                ->in('tag.tagid', $tags)
                                ->where(['thread.fid'=>$fid, 'thread.price'=>0, 'thread.readperm'=>0])
                                ->whereWhere('thread.typeid', '!=', 0)
                                ->order('thread.displayorder desc, thread.lastpost desc')
                                ->select('thread.tid, type.name, thread.author, thread.authorid, thread.subject, thread.lastpost, thread.digest, thread.highlight, thread.bgcolor');
        }
    }

    public function getThread($tid) {
        return $this->table('forum_thread')->where('tid', $tid)->find('subject, fid, author, authorid, tid, dateline, stamp');
    }

    public function getThreadClass($fid) {
        return $this->table('forum_threadclass')->where('fid', $fid)->select();
    }

    public function getGroupTags($fid, $profile = false) {
        if($profile){
            return $this->table('common_tagitem')
                ->ass('it')
                ->join(' LEFT JOIN '.$this->prefix.'common_tag AS ta ON ta.tagid = it.tagid')
                ->where(['it.itemid'=>$fid, 'it.idtype'=>'groupid'])
                ->select('it.tagid, ta.tagname');
        }
        return $this->table('common_tagitem')->where(['itemid'=>$fid, 'idtype'=>'groupid'])->select();
    }

    public function getTiezi($tid, $fid, $pid = null, $type = 0, $start = 0, $count = 10) {
        $where = ['tid'=>$tid, 'fid'=>$fid];
        if(!$pid){
            $tiezis = $this->table('forum_post')
                ->where($where)
                ->order('position asc')
                ->limit($start.' ,'.$count)
                ->select();
        }else{
            $type = $type?'>=':'>';
            $tiezis = $this->table('forum_post')
                ->where($where)
                ->whereWhere('pid', $type, $pid)
                ->order('position asc')
                ->limit($start.' ,'.$count)
                ->select('pid, fid, tid, author, authorid, subject, message, anonymous');
        }
        foreach ($tiezis as $k=>$tiezi){
            $tiezis[$k]['usericon'] = $this->getAvatar($tiezi['authorid']);
        }
        return $tiezis;
    }

    public function saveThread($fid, $uid, $username, $subject, $typeid, $attachmentCount = 0) {
        return $this->table('forum_thread')->store([
            'fid' => $fid,
            'typeid'=>$typeid,
            'author' => $username,
            'authorid'=> $uid,
            'subject' => $subject,
            'dateline'=> getglobal('timestamp'),
            'lastpost'=> getglobal('timestamp'),
            'lastposter'=>$username,
            'status' => 32,
            'attachment'=>$attachmentCount?2:0,
            'isgroup' => 1,
            'bgcolor' => ''
        ]);
    }

    public function saveTiezi($fid, $tid, $uid, $username, $subject, $message, $attachmentCount) {
        $pid = $this->table('forum_post_tableid')->store(['pid' => null], true);
        $res = $this->table('forum_post')->store([
            'pid' => $pid,
            'fid' => $fid,
            'tid' => $tid,
            'first'=> 1,
            'author'=>$username,
            'authorid'=>$uid,
            'subject' => $subject,
            'message' => $message,
            'dateline'=> getglobal('timestamp'),
            'usesig'   => 1,
            'bbcodeoff'=> -1,
            'smileyoff' => -1,
            'attachment' => $attachmentCount?2:0,
            'useip'   => getglobal('clientip'),
            'port'=>getglobal('remoteport'),
            'position'=> 1
        ]);
        return $res ? $pid : false;
    }

    public function addToNewThread($tid, $fid) {
        return $this->table('forum_newthread')->store([
            'tid' => $tid,
            'fid' => $fid,
            'dateline' => getglobal('timestamp')
        ], false);
    }

    public function updateAttachment($aids, $tid, $pid) {
        $this->table('forum_attachment')->in('aid', $aids)->update(['tid'=>$tid, 'pid'=>$pid]);
        foreach ($aids as $aid){
            $index = $this->table('forum_attachment')->where('aid', $aid)->find();
            $tableid = $index['tableid'];
            $this->table('forum_attachment_'.$tableid)->where('aid', $aid)->update(['tid'=>$tid, 'pid'=>$pid]);
        }
    }

    public function updateThreadData($fid, $tid, $author, $uid, $subject, $admin = false) {
        //保存用户日志
        useractionlog($uid, 'tid');
        //增加圈子积分
        $this->updateGroupCredits($fid);
        //更新用户家园信息
        C::t('common_member_field_home')->update($uid, array('recentnote'=>$this->$subject));
        //更新趋势
        updatestat('groupthread');
        if($admin){
            updatemoderate('tid', $tid);
        }
        updatepostcredits('+',  [$uid], 'post', $fid);
        C::t('common_member_field_home')->update($uid, array('recentnote'=>$subject));
        C::t('forum_groupuser')->update_counter_for_user($uid, $fid, 1);
        $subject = str_replace("\t", ' ', $subject);
        $lastpost = "$tid\t".$subject."\t".TIMESTAMP."\t$author";
        C::t('forum_forum')->update($fid, array('lastpost' => $lastpost));
        C::t('forum_forum')->update_forum_counter($fid, 1, 1, 1);
        C::t('forum_forumfield')->update($fid, array('lastupdate' => time()));
        require_once libfile('function/grouplog');
        updategroupcreditlog($fid, $uid);
        C::t('forum_sofa')->insert(array('tid' => $tid, 'fid' => $fid));
    }
}