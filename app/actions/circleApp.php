<?php
require_once 'circleApi.php';

class circleApp extends circleApi {
    public function home() {
        $this->checkParam(['fid', 'uid'], 'get', 1);
        $fid = $this->request->get('fid');
        $uid = $this->request->get('uid');
        $users = $this->getGroupUsers($fid, null, 1, 6);
        $profile = $this->getGroupProfile($fid, $uid);
        $wait = $this->getGroupUsers($fid, 5, false, 1);
        require_once ROOT.'h5'.__.'circle_home.php';
    }

    public function member() {
        $this->checkParam('fid','get',1);
        $fid = $this->request->get('fid');
        $users = $this->getGroupUsers($fid, null, -1, 6);
        require_once ROOT.'h5'.__.'circle_member.php';
    }
}