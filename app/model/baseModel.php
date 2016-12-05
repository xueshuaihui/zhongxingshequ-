<?php



class baseModel {
    public function __construct() {
        $this->loadcore();
    }

    private function loadcore() {
        require_once ROOT.'source/class/class_core.php';
        $discuz = C::app();
        $discuz->init_cron = false;
        $discuz->init_session = false;
        $discuz->init();
    }
}