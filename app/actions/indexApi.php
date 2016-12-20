<?php

require_once 'baseApi.php';
require_once REPOSITORY.'indexRepository.php';

class indexApi extends baseApi {
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new indexRepository();
    }

    /**
     * @SWG\Get(
     *   path="index-banner",
     *   tags={"首页相关"},
     *   summary="轮播图",
     *   description="获取轮播图",
     *   operationId="banner",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function banner() {
        return $this->tool->getBanner();
    }

    /**
     * @SWG\Get(
     *   path="index-hdzl",
     *   tags={"首页相关"},
     *   summary="获取互动专栏信息",
     *   description="获取互动专栏信息",
     *   operationId="hdzl",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function hdzl() {
        return $this->tool->getHdzl();
    }

    /**
     * @SWG\Post(
     *   path="index-quiz",
     *   tags={"首页相关"},
     *   summary="获取互动专栏【子版块】信息",
     *   description="获取互动专栏【子版块】信息",
     *   operationId="quiz",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="fid", in="formData", description="父级分类的fid", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function quiz() {
        $this->checkParam('fid');
        $fid = $this->request->post('fid');
        return $this->tool->getHdzl($fid);
    }

    /**
     * @SWG\Get(
     *   path="index-tabs",
     *   tags={"首页相关"},
     *   summary="获取信息流分类信息",
     *   description="获取信息流分类信息",
     *   operationId="tabs",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function tabs() {
        return $this->tool->getTabs();
    }

    /**
     * @SWG\Post(
     *   path="index-information",
     *   tags={"首页相关"},
     *   summary="获取信息流内容",
     *   description="获取信息流内容，需传入【板块ID，和页码ID】",
     *   operationId="information",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="typeid", in="formData", description="信息类别ID", required=true, type="string"),
     *     @SWG\Parameter(name="page", in="formData", description="页码", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function information(){
        $this->checkParam(['typeid', 'page']);
        $typeId = $this->request->post('typeid');
        $page = $this->request->post('page');
        $data = $this->tool->getInfo($typeId, $page);
        return array_values($data);
    }

    /**
     * @SWG\Post(
     *   path="index-postQuestion",
     *   tags={"首页相关"},
     *   summary="首页向专家提问",
     *   description="首页向专家提问",
     *   operationId="postQuestion",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="fid", in="formData", description="最子层板块ID", required=true, type="string"),
     *     @SWG\Parameter(name="question", in="formData", description="问题内容", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function postQuestion() {
        $this->checkParam(['uid', 'fid', 'question']);
        $uid = $this->request->post('uid');
        $fid = $this->request->post('fid');
        $question = $this->request->post('question');
        return $this->tool->pushQuestion($uid, $fid, $question);
    }

    /**
     * @SWG\Post(
     *   path="index-addCredit",
     *   tags={"首页相关"},
     *   summary="签到+",
     *   description="发帖或者登陆后请求，增加积分请求，会自动过滤，只要请求即可",
     *   operationId="addCredit",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="type", in="formData", description="类型0->daylogin, 1->post, 2->reply", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function addCredit() {
        $this->checkParam('uid');
        $uid = $this->request->post('uid');
        $type = $this->request->post('type');
        $typeArr = [['daylogin', '每日签到 积分+%d'],
                    ['post', '发表帖子 金钱+%d'],
                    ['reply', '发表回复 金钱+%d']];
        $res =$this->tool->creditHook($uid, $typeArr[$type][0]);
        if(is_numeric($res)){
            return sprintf($typeArr[$type][1], $res);
        }
        return true;
    }

    /**
     * @SWG\Get(
     *   path="index-version",
     *   tags={"首页相关"},
     *   summary="版本控制",
     *   description="版本控制",
     *   operationId="addCredit",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function version() {
        $name = $this->tool->getSetting('version_name');
        $code = $this->tool->getSetting('version_code');
        $desc = $this->tool->getSetting('version_description');
        $url = $this->tool->getSetting('version_url');
        return ['name'=>$name['svalue'], 'code'=>$code['svalue'], 'description'=>$desc['svalue'], 'url'=>$url['svalue']];
    }
}