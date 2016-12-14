<?php
require_once 'circleApi.php';

class circleApp extends circleApi {
    public function home() {
        $this->checkParam(['fid', 'uid'], 'get', 1);
        $fid = $this->request->get('fid');
        $uid = $this->request->get('uid');
        $usersCount = array_values($this->tool->countGroupUser($fid));
        $users = $this->getGroupUsers($fid, null, 1, 6);
        $profile = $this->getGroupProfile($fid, $uid);
        $wait = $this->getGroupUsers($fid, 5, false, 1);
        require_once ROOT.'h5'.__.'circle_home.php';
    }

    public function member() {
        $this->checkParam(['fid','uid','type'],'get',1);
        $fid = $this->request->get('fid');
        $uid = $this->request->get('uid');
        $type = $this->request->get('type');
        //type 0: 非转让 1: 转让
        $users = $this->getGroupUsers($fid);
        require_once ROOT.'h5'.__.'circle_member.php';
    }
}