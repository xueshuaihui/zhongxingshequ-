<?php
require_once 'baseRepository.php';

class messageRepository extends baseRepository {
    public function getPublic($mid, $page, $count = 10) {
        if($mid){
            return $this->table('forum_announcement')->where('id', $mid)->find();
        }elseif($page){
            $start = ($page - 1)*$count;
            return $this->table('forum_announcement')->whereWhere('starttime', '<', time())->whereWhere('endtime', '>', time())->order('displayorder')->limit($start.','.$count)->select('id, subject, message, starttime');
        }
        return false;
    }

    public function sendPersonMessage($uid, $touid, $message) {
        return $this->table()->sendPm($uid, $touid, $message);
    }

    public function getTips($uid, $page, $count = 10) {
        $start = ($page - 1)*$count;
        $res = $this->table('home_notification')
                    ->where(['uid'=>$uid, 'new'=>1])
                    ->order('dateline')
                    ->limit($start.','.$count)
                    ->select();
        $ids = [];
        foreach ($res as $k=>$item){
            $ids[] = $item['id'];
            $res[$k]['message'] = preg_replace('/\<.*?\>/', '', $item['note']);
            $res[$k]['message'] = str_replace('&nbsp;', '', $res[$k]['message']);
        }
//        if(count($ids) > 0){
//            $this->table('home_notification')->in('id', $ids)->update(['new'=>0]);
//        }
        return $res;
    }

    public function getPm($uid, $touid, $page, $count = 10) {
        require_once ROOT.'config/config_ucenter.php';
        require_once ROOT.'uc_client/client.php';
        $result = [];
        if(!$touid){
            $list = uc_pm_list($uid, $page, $count, 'inbox', 'privatepm', 200);
            $records = $list['data'];
            foreach ($records as $k=>$record){
                $result[$k]['touid'] = $record['touid'];
                $result[$k]['lastupdate'] = $record['lastupdate'];
                $result[$k]['lastauthor'] = $record['lastauthor'];
                $result[$k]['lastauthorid'] = $record['lastauthorid'];
                $result[$k]['message'] = ($record['message']);
                $result[$k]['isnew'] = $record['isnew'];
            }
        }else{
            $list = uc_pm_view($uid, 0, $touid, 5, $page, $count, 0, 0);
            foreach ($list as $k=>$record){
                $result[$k]['touid'] = $record['authorid'];
                $result[$k]['message'] = $record['message'];
                $result[$k]['author'] = $record['author'];
                $result[$k]['dateline'] = $record['dateline'];
                $result[$k]['position'] = ($record['authorid'] == $uid) ? 'r' : 'l';
            }
        }
        return $result;
    }
}