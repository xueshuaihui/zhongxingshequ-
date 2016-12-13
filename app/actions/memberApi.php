<?php
require_once 'baseApi.php';
require_once REPOSITORY.'memberRepository.php';

class memberApi extends baseApi {
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new memberRepository();
    }

    /**
     * @SWG\Post(
     *   path="member-changeAvatar",
     *   tags={"用户信息"},
     *   summary="绑定手机号",
     *   description="绑定手机号",
     *   operationId="changeAvatar",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="avatar", in="formData", description="图片jpg", required=true, type="file"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function changeAvatar() {
        $uid = $this->request->post('uid');
        $avatar = $this->request->file('avatar');
        $url = $this->tool->uploadImages($avatar, 'avatar');
        return $this->tool->updateUserProfile($uid, ['avatar'=>$url]);
    }
    public function changeProfile() {
        $uid = $this->request->post('uid');
        $key = $this->request->post('key');
        $val = $this->request->post('value');
    }
}