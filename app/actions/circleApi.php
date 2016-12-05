<?php
require_once 'baseApi.php';
require_once REPOSITORY.'circleRepository.php';

class circleApi extends baseApi {
    protected $tool;

    public function __construct() {
        parent::__construct();
        $this->tool = new circleRepository();
    }

    /**
     * @SWG\Post(
     *   path="circle-circleList",
     *   tags={"圈子相关"},
     *   summary="获取圈子列表",
     *   description="获取圈子列表，type:0 全部圈子； 1 我管理的； 2 我的圈子",
     *   operationId="circleList",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="当前登录用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="type", in="formData", description="圈子类型", required=true, type="string"),
     *     @SWG\Parameter(name="page", in="formData", description="页码", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function circleList() {
        $this->checkParam(['type', 'page', 'uid']);
        $type = $this->request->post('type');
        $page = $this->request->post('page');
        $uid = $this->request->post('uid');
        return $this->tool->getCircleList($uid, $type, $page);
    }

    /**
     * @SWG\Post(
     *   path="circle-circleSearch",
     *   tags={"圈子相关"},
     *   summary="搜索圈子",
     *   description="搜索圈子",
     *   operationId="circleSearch",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="keyword", in="formData", description="关键字", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function circleSearch() {
        $this->checkParam('keyword');
        $keyword = $this->request->post('keyword');
        return $this->tool->searchCircle($keyword);
    }

    public function inviteFriend() {
        
    }

    public function agreeInvite() {

    }

    /**
     * @SWG\Post(
     *   path="circle-applyJoinCircle",
     *   tags={"圈子相关"},
     *   summary="申请加入圈子",
     *   description="申请加入圈子",
     *   operationId="applyJoinCircle",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="fid", in="formData", description="群组ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function applyJoinCircle() {
        $this->checkParam(['uid', 'fid']);
        $uid = $this->request->post('uid');
        $fid = $this->request->post('fid');
        $user = $this->tool->getUserByUid($uid);
        $res = $this->tool->applyJoin($uid, $fid, $user['username']);
        if($res) {
            $circleBase = $this->tool->getGroup($fid);
            $bzIds = $this->tool->getGroupUser($fid, 'uid');
            foreach ($bzIds as $k=>$ids){
                $bzIds[$k] = $ids['uid'];
            }
            $note = '<a href="home.php?mod=space&uid='.$uid.'">'.$user['username'].'</a> 加入您的 <a href="forum.php?mod=group&fid='.$fid.'" target="_blank">'.$circleBase['name'].'</a> 群组需要审核，请到群组<a href="forum.php?mod=group&action=manage&op=checkuser&fid='.$fid.'" target="_blank">管理后台</a> 进行审核';
            $this->tool->sendMessage($bzIds, 'group', $note);
        }
        return true;
    }

    public function circleMove() {
        
    }

    public function quitCircle() {
        
    }

    public function createCircle() {
        
    }

    public function ignoreMessage() {
        
    }

    public function getManagePower() {

    }
}