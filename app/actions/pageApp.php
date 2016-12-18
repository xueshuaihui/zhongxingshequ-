<?php
require_once 'pageApi.php';

class pageApp extends pageApi {
    public function pageList() {
        $this->checkParam(['fid', 'uid'], 'get', true);
        $fid = $this->request->get('fid');
        $uid = $this->request->get('uid');
        $page = $this->request->get('page')?:1;
        $list = $this->threadList($uid, $fid, $page);
        dd($list);
        require_once ROOT.'h5'.__.'page_list.php';
    }

    public function pageContent() {
        $this->checkParam(['fid', 'tid', 'uid'], 'get', 1);
        $tid = $this->request->get('tid');
        $fid = $this->request->get('fid');
        $uid = $this->request->get('uid');
        $result = $this->tieziList($tid, $fid, 1);
        require_once ROOT.'h5'.__.'page_content.php';
    }

    public function my() {
        $this->checkParam(['uid', 'page'], 'get', 1);
        $uid = $this->request->get('uid');
        $page = $this->request->get('page');
        $this->tool->blank();
        $lists = $this->threadList($uid, false, $page);
        $avatar = $this->tool->getAvatar($uid);
        $user = $this->tool->getUserByUid($uid);
        require_once ROOT.'h5'.__.'page_my.php';
    }
}