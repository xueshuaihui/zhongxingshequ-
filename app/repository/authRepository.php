<?php
require_once 'baseRepository.php';

class authRepository extends baseRepository {

    public function verifyIdentity($username, $password) {
        $user = $this->getUserByUsername($username);
        if(!$user){
            return 10002; //用户名不存在
        }
        $identy = $this->identityPassword($user['uid'], $password);
        if(!$identy){
            return 10003; //密码错误
        }
        return true;
    }

    public function checkRepeat($username, $email, $tagId) {
        $userIde = $this->table('common_member')->where('username', $username)->find();
        if($userIde){
            return 10005; //用户名重复 in common
        }
        $userIde = $this->table('ucenter_members')->where('username', $username)->find();
        if($userIde){
            return 10005; //用户名重复 in ucenter
        }
        $emailIde = $this->table('common_member')->where('email', $email)->find();
        if($emailIde){
            return 10006; //邮箱重复 in common
        }
        $emailIde = $this->table('ucenter_members')->where('email', $email)->find();
        if($emailIde){
            return 10006; //邮箱重复 in ucenter
        }
        $tagIdIed = $this->table('common_tag')->where('tagid', $tagId)->find();
        if(!$tagIdIed){
            return 10007; //标签ID不存在
        }
        return true;
    }

    public function createUser($username, $password, $email, $tagId) {
        $salt = $this->randNum();
        $password = md5(md5($password).$salt);
        $res = $this->table('ucenter_members')->store([
            'username'=>$username,
            'password'=>$password,
            'email'=>$email,
            'salt'=>$salt,
            'regdate'=>time()
        ]);
        if(!$res){
            return false;
        }
        $res = $this->table('common_member')->store([
            'username'=>$username,
            'password'=>$password,
            'email'=>$email,
            'adminid' => 0,
            'groupid' => 22,
            'timeoffset'=>9999,
            'regdate'=>time()
        ]);
        if(!$res){
            return false;
        }
        return true;
    }

    public function getTagids() {
        return $this->table('common_tag')->where('status', 3)->select();
    }

    public function changPassword($uid, $newPass) {
        $salt = $this->randNum();
        $newPass = md5(md5($newPass).$salt);
        $res = $this->table('ucenter_members')->where('uid', $uid)->update(['password'=>$newPass, 'salt'=>$salt]);
        if(!$res){
            return false;
        }
        return (bool) $this->table('common_member')->where('uid', $uid)->update(['password'=>$newPass]);
    }

    public function getUserByUsername($username, $from = 'ucenter_members') {
        return $this->table($from)->where('username', $username)->find();
    }

    public function identityPassword($uid, $password) {
        $user = $this->table('ucenter_members')->where('uid', $uid)->find();
        return (md5(md5($password).$user['salt']) == $user['password']);
    }

    public function checkHadPhoneReturnUser($phone) {
        return $this->table('common_member_profile')->where('mobile', $phone)->find();
    }

    public function updateUserProfile($uid, $newdata = []) {
        return $this->table('common_member_profile')->where('uid', $uid)->update($newdata);
    }

    public function getUserProfile($where = []) {
        return $this->table('common_member_profile')->where($where)->find();
    }

    public function getAllUserProfile($key, $value) {
        return $this->table('common_member')
                    ->ass('c')
                    ->join(' LEFT JOIN zx_common_member_profile AS p ON c.uid = p.uid')
                    ->where('c.'.$key, $value)
                    ->find();
    }
}