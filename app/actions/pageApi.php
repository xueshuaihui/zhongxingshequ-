<?php
require_once 'baseApi.php';
require_once REPOSITORY.'pageRepository.php';

class pageApi extends baseApi {
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new pageRepository();
    }

    public function postPage() {

    }

    public function threadView() {
        
    }

    /**
     * @SWG\Post(
     *   path="page-threadList",
     *   tags={"帖子相关"},
     *   summary="帖子列表",
     *   description="邀请好友加入圈子",
     *   operationId="threadList",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="fid", in="formData", description="群组ID", required=true, type="string"),
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="page", in="formData", description="页码，0表示获取全部", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function threadList() {
        $this->checkParam(['uid', 'fid', 'page']);
        $uid = $this->request->post('uid');
        $fid = $this->request->post('fid');
        $page = $this->request->post('page');

        //获取用户标签
        $userTags = $this->tool->getUserTags($uid);
        foreach ($userTags as $k=>$userTag){
            $userTags[$k] = $userTag['tagid'];
        }
        //根据用户标签获取帖子
        $pagesData = $this->tool->getPages($fid, $page, $userTags);
        $colorArr = ['black', 'red', 'orange', 'brown', 'green', 'lightblue', 'blue', 'blueviolet', 'pink'];
        foreach ($pagesData as $k=>$value){
            $pagesData[$k]['icon'] = $this->tool->getAvatar($value['authorid']);
            if(strlen($value['highlight']) == 2){
                $highlightStyle = substr($value['highlight'], 0, 1);
                $highlightColor = substr($value['highlight'], -1);
            }else{
                $highlightStyle = 0;
                $highlightColor = $value['highlight'];
            }
            $highlightStyle = decbin($highlightStyle);
            $pagesData[$k]['B'] = $highlightStyle{2}?:'0';
            $pagesData[$k]['I'] = $highlightStyle{1}?:'0';
            $pagesData[$k]['U'] = $highlightStyle{0}?:'0';
            $pagesData[$k]['color'] = $colorArr[$highlightColor];
            unset($pagesData[$k]['highlight']);
        }
        return $pagesData;
    }
}