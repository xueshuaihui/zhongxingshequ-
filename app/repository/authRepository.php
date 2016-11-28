<?php
require_once 'baseRepository.php';

class authRepository extends baseRepository {

    public function verifyIdentity($username, $password) {
        $table = $this->table('ucenter_members');
        $ideUsername = $table->where(['username' => $username])->find();
        if(!$ideUsername){
            return 10002; //用户名不存在
        }
        $salt = $ideUsername['salt'];
        if(md5(md5($password).$salt) !== $ideUsername['password']){
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

    public function changPassword($uid, $oldPass, $newPass) {
        $user = $this->table('ucenter_members')->where('uid', $uid)->find();
        if(!$user){
            return false;
        }
        if(md5(md5($oldPass).$user['salt']) !== $user['password']){
            return 10008; //密码错误
        }
        $newPass = md5(md5($newPass).$user['salt']);
        $res = $this->table('ucenter_members')->where('uid', $uid)->update(['password'=>$newPass]);
        if(!$res){
            return 10007;
        }
        return $this->table('common_member')->where('uid', $uid)->update(['password'=>$newPass]);
    }
}