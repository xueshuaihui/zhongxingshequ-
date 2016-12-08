<?php
require_once 'baseRepository.php';

class messageRepository extends baseRepository {
    public function getPublic($page, $count = 10) {
        $start = ($page - 1)*$count;
        return $this->table('forum_announcement')->whereWhere('starttime', '<', time())->whereWhere('endtime', '>', time())->order('displayorder')->limit($start.','.$count)->select('subject, message, starttime');
    }

    public function getTips($uid, $page, $count = 10) {
        $start = ($page - 1)*$count;
        return $this->table('home_notification')
                    ->where(['uid'=>$uid, 'new'=>1])
                    ->order('dateline')
                    ->limit($start.','.$count)
                    ->select();
    }
}