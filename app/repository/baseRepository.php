<?php
require_once MODEL.'mdbModel.php';

class baseRepository {
    protected function table($table){
        return mdbModel::model($table);
    }

    protected function randNum ($size = 6, $type = 2) {
        $result = '';
        $randString2 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $randString1 = '0123456789';
        $param = 'randString'.$type;
        for($i = 0; $i < $size; $i++){
            $result .= substr($$param, rand(0, strlen($$param) - 1 ), 1);
        }
        return $result;
    }

    public function getUserByUid($uid, $form = 'common_member') {
        return $this->table($form)->where('uid', $uid)->find();
    }

    public function getUserProfile($where = []) {
        return $this->table('common_member_profile')->where($where)->find();
    }

    public function getUserByUsername($username, $from = 'ucenter_members') {
        return $this->table($from)->where('username', $username)->find();
    }

    public function getAvatar($uid, $size = 'small') {
        return avatar($uid, $size, true);
    }
}