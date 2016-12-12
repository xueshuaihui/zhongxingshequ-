<?php
require_once 'authApi.php';

class memberApp extends authApi  {
    public function details() {
        $this->checkParam('uid', 'get', true);
        $uid = $this->request->get('uid');
        $myuid = $this->request->get('myuid')?:$uid;
        $userProfile = $this->tool->getAllUserProfile('uid', $uid);
        $avatar = $this->tool->getAvatar($uid);
        $userCount = $this->tool->getUserCount($uid);
        $userGroup = $this->tool->getUserGroup($userProfile['groupid']);
        require_once ROOT.'h5'.__.'member_details.php';
    }

}