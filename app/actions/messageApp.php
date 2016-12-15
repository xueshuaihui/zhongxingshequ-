<?php
require_once 'messageApi.php';

class messageApp extends messageApi   {
    public function pm() {
        $this->checkParam(['uid','page'], 'get', true);
        $uid = $this->request->get('uid');
        $page = $this->request->get('page');
        $list = $this->getPm($uid, $page);
        require_once ROOT.'h5'.__.'message_pm_list.php';
    }

    public function pmc() {
        $this->checkParam(['uid', 'touid'], 'get', 1);
        $uid = $this->request->get('uid');
        $touid = $this->request->get('touid');
        $this->tool->blank();
        $userIcon = $this->tool->getAvatar($uid);
        require_once ROOT.'h5'.__.'message_pm_details.php';
    }
    public function pt() {
        $this->checkParam('page', 'get', 1);
        $page = $this->request->get('page');
        $tips = $this->getPublicMessage(null, $page);
        require_once ROOT.'h5'.__.'message_public.php';
    }

    public function ptc() {
        $this->checkParam('mid', 'get', 1);
        $mid = $this->request->get('mid');
        $tip = $this->getPublicMessage($mid);
        require_once ROOT.'h5'.__.'message_public_content.php';
    }

    public function tips() {
        $this->checkParam(['uid', 'page'], 'get', 1);
        $uid = $this->request->get('uid');
        $page = $this->request->get('page');
        $tips = $this->getTips($uid, $page);
        require_once ROOT.'h5'.__.'message_tips.php';
    }
}