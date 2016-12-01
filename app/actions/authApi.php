<?php

require_once 'baseApi.php';
require_once RESPOSITORY.'authRepository.php';

class authApi extends baseApi {
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new authRepository();
    }
    /**
     * @SWG\Post(
     *   path="auth-login",
     *   tags={"用户相关"},
     *   summary="登录",
     *   description="用户登录,登录成功后将返回数据为到期的时间戳，否则将会提示错误，并返回错误代码",
     *   operationId="login",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="username", in="formData", description="用户名", required=true, type="string"),
     *     @SWG\Parameter(name="password", in="formData", description="密码", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */

    public function login() {
        $this->checkParam(['username', 'password']);
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $res = $this->tool->verifyIdentity($username, $password);
        if(is_bool($res) && $res){
            $profile = $this->tool->getAllUserProfile('username', $username);
            return [strtotime('+7 day'), $profile];
        }
        return $res;
    }

    /**
     * @SWG\Post(
     *   path="auth-register",
     *   tags={"用户相关"},
     *   summary="注册",
     *   description="用户注册功能, 此接口前，请先获取注册页面的接口",
     *   operationId="register",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="username", in="formData", description="用户名", required=true, type="string"),
     *     @SWG\Parameter(name="password", in="formData", description="密码", required=true, type="string"),
     *     @SWG\Parameter(name="email", in="formData", description="邮箱", required=true, type="string"),
     *     @SWG\Parameter(name="tagid", in="formData", description="公司标签ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function register() {
        $this->checkParam(['username', 'password', 'email', 'tagid']);
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $email = $this->request->post('email');
        $tagid = $this->request->post('tagid');

        //验证用户名, 邮箱, 标签ID可用性
        $res = $this->tool->checkRepeat($username, $email, $tagid);
        if(is_numeric($res)){
            return $res;
        }

        //保存信息
        return $this->tool->createUser($username, $password, $email, $tagid);
    }

    /**
     * @SWG\Get(
     *   path="auth-pregister",
     *   tags={"用户相关"},
     *   summary="注册之前，用于获取tagid",
     *   description="获取注册页面的接口",
     *   operationId="pregister",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function pregister() {
        return $this->tool->getTagIds();
    }

    /**
     * @SWG\Post(
     *   path="auth-changPassword",
     *   tags={"用户相关"},
     *   summary="更改用户登录密码",
     *   description="更改用户登录密码",
     *   operationId="changPassword",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="oldPassword", in="formData", description="旧密码", required=true, type="string"),
     *     @SWG\Parameter(name="newPassword", in="formData", description="新密码", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function changPassword() {
        $this->checkParam(['uid', 'oldPassword', 'newPassword']);
        $uid = $this->request->post('uid');
        $oldPassword = $this->request->post('oldPassword');
        $newPassword = $this->request->post('newPassword');
        $identy = $this->tool->identityPassword($uid, $oldPassword);
        if(!$identy){
            return 10008;
        }
        return $this->tool->changPassword($uid, $newPassword);
    }

    /**
     * @SWG\Post(
     *   path="auth-resetPassword",
     *   tags={"用户相关"},
     *   summary="找回用户登录密码",
     *   description="找回用户登录密码",
     *   operationId="resetPassword",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="phone", in="formData", description="手机号", required=true, type="string"),
     *     @SWG\Parameter(name="code", in="formData", description="验证码", required=true, type="string"),
     *     @SWG\Parameter(name="newPassword", in="formData", description="新密码", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function resetPassword() {
        $this->checkParam(['phone', 'code', 'newPassword']);
        $phone = $this->request->post('phone');
        $code = $this->request->post('code');
        $newPassword = $this->request->post('newPassword');
        //验证验证码是否正确
        $serverCode = $this->request->session('reset.'.$phone);
        if(!$serverCode || $serverCode != $code){
            return 10010; //验证码错误
        }
        //验证手机号是否存在,存在则返回用户
        $user = $this->tool->checkHadPhoneReturnUser($phone);
        if(!$user){
            return 10009; //该手机没有绑定任何用户
        }
        return $this->tool->changPassword($user['uid'], $newPassword);
    }

    /**
     * @SWG\Post(
     *   path="auth-blind",
     *   tags={"用户相关"},
     *   summary="绑定手机号",
     *   description="绑定手机号",
     *   operationId="blind",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="phone", in="formData", description="手机号", required=true, type="string"),
     *     @SWG\Parameter(name="code", in="formData", description="验证码", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function blind() {
        $this->checkParam(['uid', 'phone', 'code']);
        $uid = $this->request->post('uid');
        $phone = $this->request->post('phone');
        $code = $this->request->post('code');
        $serverCode = $this->request->session('blind.'.$phone);
        if(!$serverCode || $serverCode != $code){
            return 10010; //验证码错误
        }
        $user = $this->tool->getUserProfile(['mobile'=>$phone]);
        if($user){
            return 10011; //该手机号已被绑定其他账号
        }
        return (bool) $this->tool->updateUserProfile($uid, ['mobile'=>$phone]);
    }
}