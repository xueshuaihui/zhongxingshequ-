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
                         ->order('displayorder desc, lastpost desc')
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
}