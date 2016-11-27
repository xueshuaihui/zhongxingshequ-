<?php

require_once 'baseApi.php';
require_once RESPOSITORY.'authRepository.php';

class authApi extends baseApi {
    protected $ucenter;
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new authRepository();
    }
    /**
     * @SWG\Post(
     *   path="auth-login",
     *   tags={"login"},
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
            return strtotime('+7 day');
        }
        return $res;
    }

    /**
     * @SWG\Post(
     *   path="auth-register",
     *   tags={"register"},
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
     * @SWG\Post(
     *   path="auth-pregister",
     *   tags={"pregister"},
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
     *   tags={"changPassword"},
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
        return $this->tool->changPassword($uid, $oldPassword, $newPassword);
    }

    private function checkParam ($params = []) {
        foreach ($params as $param){
            if(is_null($this->request->post($param))){
                return 10001;//缺少参数
            }
        }
    }
}