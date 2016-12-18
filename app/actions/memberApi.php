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
     *   summary="修改用户头像",
     *   description="修改用户头像",
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
        $res = $this->tool->uploadAvatar($avatar, $uid);
        if(is_bool($res) && $res){
            $this->tool->blank();
            return $this->tool->getAvatar($uid, 'big');
        }else{
            return $res;
        }
    }

    /**
     * @SWG\Post(
     *   path="member-changeProfile",
     *   tags={"用户信息"},
     *   summary="修改用户昵称",
     *   description="修改用户昵称",
     *   operationId="changeProfile",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="key", in="formData", description="名称还是签名name or bio", required=true, type="string"),
     *     @SWG\Parameter(name="value", in="formData", description="值", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function changeProfile() {
        $this->checkParam(['uid', 'key', 'value']);
        $uid = $this->request->post('uid');
        $key = $this->request->post('key');
        $val = $this->request->post('value');
        if($key != 'bio' && $key != 'name'){
            return 10007;
        }
        if($key == 'name'){
            $key = 'field3';
        }
        return $this->tool->updateUserProfile($uid, [$key=>$val]);
    }

    /**
     * @SWG\Post(
     *   path="member-searchFriend",
     *   tags={"用户信息"},
     *   summary="好友搜索",
     *   description="好友搜索",
     *   operationId="searchFriend",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="keyword", in="formData", description="关键字", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function searchFriend() {
        $this->checkParam(['uid', 'keyword']);
        $uid = $this->request->post('uid');
        $keyword = $this->request->post('keyword');
        return $this->tool->searchFriend($uid, $keyword);
    }

    /**
     * @SWG\Post(
     *   path="member-getCredits",
     *   tags={"用户信息"},
     *   summary="获取积分",
     *   description="获取积分",
     *   operationId="getCredits",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function getCredits() {
        $this->checkParam('uid');
        $uid = $this->request->post('uid');
        $user = $this->tool->getUserByUid($uid);
        return $user['credits'];
    }


    /**
     * @SWG\Post(
     *   path="member-applyFriend",
     *   tags={"用户信息"},
     *   summary="申请加好友",
     *   description="申请加好友",
     *   operationId="applyFriend",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="who", in="formData", description="加谁？ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function applyFriend() {
        $this->checkParam(['uid', 'who']);
        $uid = $this->request->post('uid');
        $who = $this->request->post('who');
        $me = $this->tool->getUserByUid($uid);
        if(!$me){
            return 10007;
        }
        $check = $this->tool->addFriendApply($uid, $who);
        if($check){
            return 10026;
        }
        $res = $this->tool->addFriendApply($uid, $who, $me['username']);
        if($res || 1){
            $note = '<a href="home.php?mod=space&uid='.$uid.'">'.$me['username'].'</a> 请求加您为好友&nbsp;&nbsp;<a onclick="showWindow(this.id, this.href, \'get\', 0);" class="xw1" id="afr_1" href="home.php?mod=spacecp&ac=friend&op=add&uid='.$uid.'&from=notice">批准申请</a>';
            $this->tool->sendMessage($who, 'friend', $note);
        }else{
            return true;
        }
        return true;
    }
}