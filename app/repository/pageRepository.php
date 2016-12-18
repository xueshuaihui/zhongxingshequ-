<?php
require_once 'baseRepository.php';

class pageRepository extends baseRepository {
    public function getUserTags($uid) {
        return $this->table('common_tagitem')->where(['idtype'=>'uid','itemid'=>$uid])->select('tagid');
    }

    public function getPages($fid, $uid, $page, $tags = null, $keyword = null, $count = 10) {
        $data = $this->table('forum_thread')->ass('thread')->join(' LEFT JOIN '.$this->prefix.'common_tagitem AS tag ON tag.itemid = thread.tid AND tag.idtype = \'threadid\'')->join(' LEFT JOIN '.$this->prefix.'forum_threadclass AS type ON type.typeid = thread.typeid');
        if($uid){
            $data->where('thread.authorid', $uid);
        }
        if($fid){
            $data->where('thread.fid', $fid);
        }
        if($keyword){
            $data->whereWhere('subject', 'LIKE', "%{$keyword}%");
        }
        if($tags && $tags != 'admin'){
            $data->in('tag.tagid', $tags);
        }elseif(is_null($tags)){
            $data->isNull('tag.tagid');
        }
        $data->where(['thread.price'=>0, 'thread.readperm'=>0])->whereWhere('thread.typeid', '!=', 0)->order('thread.displayorder desc, thread.lastpost desc');
        if($page){
            $start = ($page - 1) * $count;
            $data->limit($start.' ,'.$count);
        }
         return $data->select('thread.tid, thread.fid, type.name, thread.author, thread.authorid, thread.subject, thread.lastpost, thread.digest, thread.highlight, thread.bgcolor, thread.stamp, thread.displayorder');
    }

    public function getThread($tid) {
        return $this->table('forum_thread')->where('tid', $tid)->find('subject, fid, author, authorid, tid, dateline, stamp, maxposition');
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

    public function getTiezi($tid, $fid, $pid = null, $page = null, $count = 10) {
        $data = $this->table('forum_post')->where(['tid'=>$tid, 'fid'=>$fid])->order('position asc');
        if($page){
            $start = ($page - 1)*$count;
            $data->limit($start.','.$count);
        }elseif($pid){
            $data->whereWhere('pid', '>=', $pid)->limit('0,'.$count);
        }
        $tiezis = $data->select('pid, fid, tid, position, author, authorid, subject, message, anonymous, dateline');
        foreach ($tiezis as $k=>$tiezi){
            $tiezis[$k]['usericon'] = $this->getAvatar($tiezi['authorid']);
        }
        return $tiezis;
    }

    public function getTheTiezi($tid, $fid, $pid) {
        $data = $this->table('forum_post')->where(['tid'=>$tid, 'fid'=>$fid, 'pid'=>$pid])->find('pid, fid, tid, position, author, authorid, subject, message, anonymous, dateline');
        $data['usericon'] = $this->getAvatar($data['authorid']);
        return $data;
    }

    public function formatTiezi($item) {
        $itemBak = $item;
        $item['message'] = preg_replace('/\[quote\]([\s\S]*)\[\/quote\]/i', '', $item['message']);
        $item['message'] = preg_replace('/\[.*?\]/', '', $item['message']);
        $item['message'] = preg_replace('/(https|http):\/\/(.*?)(png|jpeg|gif|jpg)/i', '', $item['message']);
        $item['message'] = trim($item['message']);
        preg_match_all('/\[attach\].*?\[\/attach\]/', $itemBak['message'], $res);
        preg_match('/\[quote\]([\s\S]*)\[\/quote\]/i', $itemBak['message'], $reply);
        $replyContent = preg_replace('/\[.*?\]/', '', $reply[0]);
        $attachId = '';
        foreach ($res[0] as $re){
            $attachId[]= trim(preg_replace('/\[.*?\]/', ' ', $re));
        }
        $item['reply'] = $replyContent?:'';
        if($attachId){
            $item['attach'] = $this->getAttach($attachId);
        }else{
            $item['attach'] = [];
        }
        //再把图片链接读出来
        $tempImg = [];
        preg_match_all('/(https|http):\/\/(.*?)(png|jpeg|gif|jpg)/i', $itemBak['message'], $imgUrl);
        foreach ($imgUrl[0] as $img){
            $tempImg[] = ['attachment'=>$img, 'isimage'=>1];
        }
        $item['attach'] = array_merge($item['attach'], $tempImg);
        return $item;
    }

    public function saveThread($fid, $uid, $username, $subject, $typeid, $attachmentCount = 0, $maxposition = 1) {
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
            'bgcolor' => '',
            'maxposition' => max($maxposition, 1)
        ]);
    }

    public function saveTiezi($fid, $tid, $uid, $replyPid, $username, $subject, $message, $attachmentCount, $maxposition = 0) {
        $first = 1;
        $bbcodeoff = -1;
        if($replyPid){
            $replyItem = $this->table('forum_post')->where('pid', $replyPid)->find();
            $replyMessage = explode(
'
', $replyItem['message']);
            $message = '[quote][size=2][url=forum.php?mod=redirect&goto=findpost&pid='.$replyPid.'&ptid='.$tid.'][color=#999999]'.$username.' 发表于 '.date('Y-m-d H:i:s', time()).'[/color][/url][/size]
'.cutstr(end($replyMessage), 100).'[/quote]'.$message;
            $first = 0;
            $bbcodeoff = 0;
        }
        if($maxposition){
            $first = 0;
        }
        $maxposition += 1;
        if($subject == '' || !$subject){
            $baseposition = 2;
        }else{
            $baseposition = 1;
        }
        $maxposition = max($maxposition, $baseposition);
        $pid = $this->table('forum_post_tableid')->store(['pid' => null], true);
        $res = $this->table('forum_post')->store([
            'pid' => $pid,
            'fid' => $fid,
            'tid' => $tid,
            'first'=> $first,
            'author'=>$username,
            'authorid'=>$uid,
            'subject' => $subject,
            'message' => $message,
            'dateline'=> getglobal('timestamp'),
            'usesig'   => 1,
            'bbcodeoff'=> $bbcodeoff,
            'smileyoff' => -1,
            'attachment' => $attachmentCount?2:0,
            'useip'   => getglobal('clientip'),
            'port'=>getglobal('remoteport'),
            'position'=> $maxposition
        ]);
        if(!$res){
            return false;
        }
        $res = $this->table('forum_thread')->where('tid', $tid)->update(['maxposition'=>$maxposition]);
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

    public function updateThreadData($fid, $tid, $author, $uid, $subject, $position, $admin = false) {
        //更新主题的maxposition
        $this->table('forum_thread')->where('tid', $tid)->update(['maxposition'=>$position+1]);
        //保存用户日志
        useractionlog($uid, 'tid');
        //增加圈子积分
        $this->updateGroupCredits($fid);
        //更新用户家园信息
        C::t('common_member_field_home')->update($uid, array('recentnote'=>$subject));
        //更新趋势
        updatestat('groupthread');
        if($admin){
            updatemoderate('tid', $tid);
        }
        if($subject != ''){
            updatepostcredits('+',  [$uid], 'post', $fid);
        }else{
            updatepostcredits('+',  [$uid], 'reply', $fid);
        }
        C::t('common_member_field_home')->update($uid, array('recentnote'=>$subject));
        C::t('forum_groupuser')->update_counter_for_user($uid, $fid, 1);
        $subject = str_replace("\t", ' ', $subject);
        $lastpost = "$tid\t".$subject."\t".TIMESTAMP."\t$author";
        C::t('forum_forum')->update($fid, array('lastpost' => $lastpost));
        C::t('forum_forum')->update_forum_counter($fid, 1, 1, 1);
        C::t('forum_forumfield')->update($fid, array('lastupdate' => time()));
        require_once libfile('function/grouplog');
        updategroupcreditlog($fid, $uid);
        if($subject != ''){
            C::t('forum_sofa')->insert(array('tid' => $tid, 'fid' => $fid));
        }
    }

    public function reply($fid, $tid, $uid, $message, $attachmentCount) {
        $pid = $this->table('forum_post_tableid')->store(['pid' => null], true);
        $thread = $this->table('forum_thread')->where('tid', $tid)->find();
        if(!$thread){
            return false;
        }
        $position = $thread['maxposition']+1;
        $author = $this->getUserByUid($uid);
        $res = $this->table('forum_post')->store([
            'pid' => $pid,
            'fid' => $fid,
            'tid' => $tid,
            'author'=>$author['username'],
            'authorid'=>$uid,
            'first' => 0,
            'subject' => '',
            'message' => $message,
            'dateline'=> getglobal('timestamp'),
            'usesig'   => 1,
            'bbcodeoff'=> -1,
            'smileyoff' => -1,
            'attachment' => $attachmentCount?2:0,
            'useip'   => getglobal('clientip'),
            'port'=>getglobal('remoteport'),
            'position'=> $position
        ]);
        if(!$res){
            return false;
        }
        $res = $this->table('forum_thread')->where('uid', $uid)->update(['maxposition'=>$position]);
        return $res ? $pid : false;
    }
}