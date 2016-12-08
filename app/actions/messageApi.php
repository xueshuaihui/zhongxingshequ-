<?php
require_once 'baseApi.php';
require_once REPOSITORY.'messageRepository.php';

class messageApi extends baseApi {
    protected $tool;

    public function __construct() {
        parent::__construct();
        $this->tool = new messageRepository();
    }

    public function sendPersonMessage() {
        echo "test webhook";
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

    public function getPersonMessage() {

    }

    public function ignoreMessage() {
        
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