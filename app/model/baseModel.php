<?php



class baseModel {
    public function __construct() {
        $this->loadcore();
    }

    private function loadcore() {
        require_once ROOT.'source/class/class_core.php';
        require_once ROOT.'source/function/function_group.php';
        $discuz = C::app();
        $discuz->init_cron = false;
        $discuz->init_session = false;
        $discuz->init();
    }

    public function mygrouplist($uid, $type, $start, $count = 10) {
        return mygrouplist($uid, 'lastupdate', array('f.name', 'ff.icon', 'ff.description'), $count, $start, $type, 0);
    }

    public function grouplist($uid, $type, $page = 1, $count = 10) {
        $start = ($page - 1) * $count;
        if($type){
            return $this->mygrouplist($uid, $type, $start, $count);
        }else{
            return grouplist('lastupdate', [], [$start, $count]);
        }
    }

    public function searchGroup($keyword) {
        return C::t('forum_forum')->fetch_all_by_keyword($keyword);
    }
}