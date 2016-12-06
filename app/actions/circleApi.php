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

    /**
     * @SWG\Post(
     *   path="circle-changeCircleFounder",
     *   tags={"圈子相关"},
     *   summary="转让圈子",
     *   description="转让圈子",
     *   operationId="changeCircleFounder",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="old_uid", in="formData", description="原圈主ID", required=true, type="string"),
     *     @SWG\Parameter(name="new_uid", in="formData", description="新圈主ID", required=true, type="string"),
     *     @SWG\Parameter(name="fid", in="formData", description="圈子ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function changeCircleFounder() {
        $this->checkParam(['old_uid', 'new_uid', 'fid']);
        $oldUid = $this->request->post('old_uid');
        $newUid = $this->request->post('new_uid');
        $fid = $this->request->post('fid');
        if($oldUid == $newUid){
            return 10007; //参数错误
        }
        $groupProfile = $this->tool->getGroupProfile($fid);
        if($groupProfile['founderuid'] != $oldUid){
            return 10014; //无权限操作
        }
        $user = $this->tool->getUserFromGroup($newUid, $fid);
        if(!$user){
            return 10012; //新用户未加入圈子
        }
        if($user['level'] != 1){
            return 10015; //该用户还不是管理员
        }
        return $this->tool->updateGroupProfile($fid, ['founderuid'=>$user['uid'], 'foundername'=>$user['username']]);
    }

    /**
     * @SWG\Post(
     *   path="circle-quitCircle",
     *   tags={"圈子相关"},
     *   summary="退出圈子",
     *   description="退出圈子",
     *   operationId="quitCircle",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="fid", in="formData", description="群组ID", required=true, type="string"),
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function quitCircle() {
        $this->checkParam(['uid', 'fid']);
        $fid = $this->request->post('fid');
        $uid = $this->request->post('uid');
        $group = $this->tool->getGroupProfile($fid);
        if($uid == $group['founderuid']){
            return 10016; //创建者不能退出圈子
        }
        return $this->tool->quit($fid, $uid);
    }

    public function createCircle() {
        
    }

    public function ignoreMessage() {
        
    }

    /**
     * @SWG\Post(
     *   path="circle-changeUserGroupStatus",
     *   tags={"圈子相关"},
     *   summary="更改用户于圈子的身份",
     *   description="更改用户于圈子的身份，可指定某用户为任何身份,验证通过也是此接口",
     *   operationId="changeUserGroupStatus",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="fid", in="formData", description="群组ID", required=true, type="string"),
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="wantPower", in="formData", description="目的权限，1：圈主；2：副圈主；3：明星成员；4：普通成员；默认为1", required=false, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function changeUserGroupStatus() {
        $this->checkParam(['uid', 'fid']);
        $uid = $this->request->post('uid');
        $fid = $this->request->post('fid');
        $wantPower = $this->request->post('wantPower') ?: 1;
        $user = $this->tool->getUserFromGroup($uid, $fid);
        if(!$user){
            return 10012; //该用户还没有加入群组
        }
        if($wantPower != 4 && $user['level'] == 0){
            return 10013; //该用户还没有通过审核
        }
        return $this->tool->updateGroupUser($uid, $fid, $wantPower);
    }

    /**
     * @SWG\Post(
     *   path="circle-getGroupUsers",
     *   tags={"圈子相关"},
     *   summary="获取圈子成员列表",
     *   description="获取圈子成员列表",
     *   operationId="getGroupUsers",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="fid", in="formData", description="群组ID", required=true, type="string"),
     *     @SWG\Parameter(name="level", in="formData", description="获取的用户属性，0：全部；1：管理员；2：副管理员；3：明星成员；4：普通成员；5：待审核成员；默认为0", required=false, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function getGroupUsers() {
        $this->checkParam('fid');
        $fid = $this->request->post('fid');
        $level = $this->request->post('level') ?: false;
        $level = $level == 5 ? 0 : $level;
        $users = $this->tool->getGroupUser($fid, 'uid, username', $level);
        foreach ($users as $k=>$user){
            $users[$k]['avatar'] = $this->tool->getAvatar($user['uid']);
        }
        return $users;
    }

//    public function getManagePower() {
//        $this->checkParam(['uid', 'fid']);
//        $uid = $this->request->post('uid');
//        $fid = $this->request->post('fid');
//        $level = $this->tool->getUserFromGroup($uid, $fid);
//        $levelArr = ['wait', 'manager', 'secManager', 'starts', 'common'];
//        return $levelArr[$level['level']];
//    }

    /**
     * @SWG\Post(
     *   path="circle-getGroupProfile",
     *   tags={"圈子相关"},
     *   summary="获取圈子详情",
     *   description="获取圈子详情,relation 为用户身份，0：非成员，1：待审核成员；2：普通成员；3：管理员；4：创建者",
     *   operationId="getGroupProfile",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="fid", in="formData", description="群组ID", required=true, type="string"),
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function getGroupProfile() {
        $this->checkParam(['fid', 'uid']);
        $fid = $this->request->post('fid');
        $uid = $this->request->post('uid');
        $profile = $this->tool->getGroupProfile($fid);
        $result['title'] = $profile['name'];
        $result['description'] = $profile['description'];
        //获取用户于群组的关系
        if($profile['founderuid'] == $uid){
            $result['relation'] = 4; //为创建者
            return $result;
        }
        //看看有没有加入圈子
        $groupUser = $this->tool->getUserFromGroup($uid, $fid);
        if(!$groupUser){
            $result['relation'] = 0; //没关系
            return $result;
        }
        //看看用户是不是网站管理员
        $user = $this->tool->getUserProfile(['uid'=>$uid]);
        if($user['adminid']){
            $result['relation'] = 3; //管理员
            return $result;
        }
        //看看你的圈子等级
        if($groupUser['level'] == 1 || $groupUser['level'] == 2){
            $result['relation'] = 3; //管理员
            return $result;
        }
        if($groupUser['level'] == 3 || $groupUser['level'] == 4){
            $result['relation'] = 2; //普通成员
            return $result;
        }
        if($groupUser['level'] == 0){
            $result['relation'] = 1; //待审核成员
            return $result;
        }
        return false;
    }
}