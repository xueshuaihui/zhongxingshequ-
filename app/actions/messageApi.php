<?php

require_once 'baseApi.php';
require_once REPOSITORY.'messageRepository.php';

class messageApi extends baseApi {
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new messageRepository();
    }

    /**
     * @SWG\Post(
     *   path="message-sendPm",
     *   tags={"消息相关"},
     *   summary="发送私信",
     *   description="发送私信",
     *   operationId="sendPm",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="touid", in="formData", description="接收用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="message", in="formData", description="消息", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function sendPm() {
        $this->checkParam(['uid', 'touid', 'message']);
        $uid = $this->request->post('uid');
        $touid = $this->request->post('touid');
        $message = $this->request->post('message');
        return $this->tool->sendPersonMessage($uid, $touid, $message);
    }

    /**
     * @SWG\Post(
     *   path="message-getPublicMessage",
     *   tags={"消息相关"},
     *   summary="获取公告",
     *   description="获取公告",
     *   operationId="getPublicMessage",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="page", in="formData", description="页码", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function getPublicMessage() {
        $this->checkParam('page');
        $page = $this->request->post('page');
        return $this->tool->getPublic($page);
    }

    /**
     * @SWG\Post(
     *   path="message-getPm",
     *   tags={"消息相关"},
     *   summary="获取私信",
     *   description="获取私信,获取私信列表和具体私信记录都用此接口",
     *   operationId="getPm",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="page", in="formData", description="页码", required=true, type="string"),
     *     @SWG\Parameter(name="touid", in="formData", description="对方用户ID，没有就不传", required=false, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function getPm() {
        $this->checkParam(['uid', 'touid', 'page']);
        $uid = $this->request->post('uid');
        $touid = $this->request->post('touid')?:0;
        $page = $this->request->post('page');
        $this->tool->blank();
        $pmList = $this->tool->getPm($uid, $touid, $page);
        foreach ($pmList as $k=>$value){
            $pmList[$k]['usericon'] = $this->tool->getAvatar($value['touid']);
        }
        return $pmList;
    }

    /**
     * @SWG\Post(
     *   path="message-getTips",
     *   tags={"消息相关"},
     *   summary="获取消息",
     *   description="获取消息",
     *   operationId="getTips",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="page", in="formData", description="页码", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function getTips() {
        $this->checkParam(['uid', 'page']);
        $uid = $this->request->post('uid');
        $page = $this->request->post('page');
        return $this->tool->getTips($uid, $page);
    }
}