<?php
require_once 'baseApi.php';
require_once RESPOSITORY.'codeRepository.php';

class codeApi extends baseApi {
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new codeRepository();
    }

    /**
     * @SWG\Post(
     *   path="code-whenResetPassword",
     *   tags={"验证码"},
     *   summary="【重置密码】验证码",
     *   description="获取重置密码，所用验证码",
     *   operationId="whenResetPassword",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="phone", in="formData", description="手机号", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function whenResetPassword() {
        $this->checkParam('phone');
        $phone = $this->request->post('phone');
        $had = $this->tool->identityHadPhone($phone);
        if(!$had){
            return 10009; //手机谁都没绑定
        }
        $code = $this->tool->getRand();
        $this->request->setSession(['reset'=>[$phone=>$code]]);
        return $code;
    }

    /**
     * @SWG\Post(
     *   path="code-whenBlinkPassword",
     *   tags={"验证码"},
     *   summary="【绑定手机】验证码",
     *   description="获取绑定手机，所用验证码",
     *   operationId="whenBlinkPassword",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="phone", in="formData", description="手机号", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function whenBlinkPassword() {
        $this->checkParam('phone');
        $phone = $this->request->post('phone');
        $had = $this->tool->identityHadPhone($phone);
        if($had){
            return 10011; //该手机号已被绑定账号
        }
        $code = $this->tool->getRand();
        $this->request->setSession(['reset'=>[$phone=>$code]]);
        return $code;
    }
}