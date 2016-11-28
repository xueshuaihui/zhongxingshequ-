<?php

require_once 'baseApi.php';
require_once RESPOSITORY.'indexRepository.php';

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
        return $this->tool->getInfo($typeId, $page);
    }
}