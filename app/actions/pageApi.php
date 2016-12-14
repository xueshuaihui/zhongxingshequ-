<?php
require_once 'baseApi.php';
require_once REPOSITORY.'pageRepository.php';

class pageApi extends baseApi {
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new pageRepository();
    }

    /**
     * @SWG\Post(
     *   path="page-wangPost",
     *   tags={"帖子相关"},
     *   summary="我要发帖",
     *   description="我要发帖",
     *   operationId="wangPost",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="fid", in="formData", description="圈子ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function wangPost() {
        $this->checkParam(['uid', 'fid']);
        $uid = $this->request->post('uid');
        $fid = $this->request->post('fid');

        $status = 0;
        $tags = [];

        //判断用户属性
        $user = $this->tool->getUserByUid($uid);
        if($user['adminid']){
            $status = 1;
        }else{
            $groupUser = $this->tool->getGroupUser($fid, $uid, false);
            if($groupUser['level'] < 3){
                $status = 1;
            }
        }

        if($status){
            //获取标签
            $tags = $this->tool->getGroupTags($fid, 1);
        }

        //获取分类
        $groupClasses = $this->tool->getThreadClass($fid);
        return ['status'=>$status, 'tags'=>$tags, 'category'=>$groupClasses];
    }

    /**
     * @SWG\Post(
     *   path="page-postPage",
     *   tags={"帖子相关"},
     *   summary="发帖提交接口",
     *   description="发帖提交接口-回复帖子接口",
     *   operationId="postPage",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="fid", in="formData", description="圈子ID", required=true, type="string"),
     *     @SWG\Parameter(name="message", in="formData", description="内容", required=true, type="string"),
     *     @SWG\Parameter(name="tid", in="formData", description="要回复的主题ID 回复时必传", required=false, type="string"),
     *     @SWG\Parameter(name="pid", in="formData", description="要回复的帖子ID 回复帖子时必传", required=false, type="string"),
     *     @SWG\Parameter(name="category", in="formData", description="分类ID 回复时不不传", required=false, type="string"),
     *     @SWG\Parameter(name="tag", in="formData", description="标签ID s eg:1,2,3 回复时不不传", required=false, type="string"),
     *     @SWG\Parameter(name="subject", in="formData", description="标题 回复时不不传", required=false, type="string"),
     *     @SWG\Parameter(name="images0", in="formData", description="图片1 看情况", required=false, type="file"),
     *     @SWG\Parameter(name="images1", in="formData", description="图片2 看情况", required=false, type="file"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function postPage() {
        $this->checkParam(['uid', 'fid', 'message']);
        $uid = $this->request->post('uid');
        $fid = $this->request->post('fid');
        $replyTid = $this->request->post('tid');
        $replyPid = $this->request->post('pid');
        $class = $this->request->post('category');
        $tags = $this->request->post('tag');
        $subject = $this->request->post('subject')?:'';
        $message = $this->request->post('message');

        if((!$message || $message == '')  && $replyPid){
            return 10021;
        }

        $attachmentCount = 0;
        if($this->request->hasFile()){
            $attachments = $this->request->file();
            $attachmentCount = count($attachments);
            $attachmentArr = $this->tool->uploadImages($attachments, 'forum');
            if(is_numeric($attachmentArr)){
                return $attachmentArr;
            }
            $attachs = $this->tool->saveAttachmentIndex($attachmentArr, $uid);
            foreach ($attachs as $attach){
                $message .= '[attach]'.$attach['aid'].'[/attach]';
            }
        }

        $tags = explode(',', $tags);

        //获取用户
        $user = $this->tool->getUserByUid($uid);
        //保存主题
        if($subject != '' && $tags && $class){
            $tid = $this->tool->saveThread($fid, $uid, $user['username'], $subject, $class, $attachmentCount);
            //添加标签绑定
            if(!$tid){
                return false;
            }
            $res = $this->tool->addBlindTag($tid, $tags, 'threadid');
            //保存帖子
            if(!$res){
                return false;
            }
            $maxposition = 0;
        }elseif($replyTid){
            $thread = $this->tool->getThread($replyTid);
            $tid = $replyTid;
            $subject = '';
            $maxposition = $thread['maxposition'];
        }else{
            return false;
        }

        $pid = $this->tool->saveTiezi($fid, $tid, $uid, $replyPid, $user['username'], $subject, $message, $attachmentCount, $maxposition);
        if(!$pid){
            return false;
        }
        //添加附件绑定
        if($attachmentCount > 0){
            $this->tool->saveAttachment($attachs, $pid, $tid, $uid);
        }
        $this->tool->updateThreadData($fid, $tid, $user['username'], $uid, $subject, $maxposition, $user['adminid']);
        return true;
    }

    /**
     * @SWG\Post(
     *   path="page-threadView",
     *   tags={"帖子相关"},
     *   summary="帖子列表-获取主题",
     *   description="获取主题详情",
     *   operationId="threadView",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="tid", in="formData", description="主题ID", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function threadView() {
        $this->checkParam('tid');
        $tid = $this->request->post('tid');
        $thread = $this->tool->getThread($tid);
        $thread['usericon'] = $this->tool->getAvatar($thread['authorid']);
        return $thread;
    }

    /**
     * @SWG\Post(
     *   path="page-tieziList",
     *   tags={"帖子相关"},
     *   summary="帖子列表-获取帖子列表",
     *   description="获取帖子列表",
     *   operationId="tieziList",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="tid", in="formData", description="主题ID", required=true, type="string"),
     *     @SWG\Parameter(name="fid", in="formData", description="论坛ID", required=true, type="string"),
     *     @SWG\Parameter(name="page", in="formData", description="页码，与pid不同时", required=false, type="string"),
     *     @SWG\Parameter(name="pid", in="formData", description="帖子ID，非下拉刷新或初始进入时，需要", required=false, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function tieziList($sTid = null, $sFid = null) {
        $this->checkParam(['tid','fid']);
        $tid = $sTid?:$this->request->post('tid');
        $fid = $sFid?:$this->request->post('fid');
        $page = $this->request->post('page');
        $pid = $this->request->post('pid');
        $pages = $this->tool->getTiezi($tid, $fid, $pid, $page);
        foreach ($pages as $k=>$page){
            $pages[$k]['message'] = preg_replace('/\[attach\].*?\[\/attach\]/', '', $pages[$k]['message']);
            preg_match_all('/\[attach\].*?\[\/attach\]/', $page['message'], $res);
            $attachId = '';
            foreach ($res[0] as $re){
                $attachId[]= trim(preg_replace('/\[.*?\]/', ' ', $re));
            }
            if($attachId){
                $pages[$k]['attach'] = $this->tool->getAttach($attachId);
            }else{
                $pages[$k]['attach'] = [];
            }
        }
        return $pages;
    }

    /**
     * @SWG\Post(
     *   path="page-threadList",
     *   tags={"帖子相关"},
     *   summary="圈子详情",
     *   description="圈子详情，也就是主题列表",
     *   operationId="threadList",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="fid", in="formData", description="群组ID,获取我的主题的时候不用", required=false, type="string"),
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="page", in="formData", description="页码，0表示获取全部", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function threadList($sUid = null, $sFid = null, $sPage = null) {
        $this->checkParam(['uid', 'page']);
        $uid = $sUid ?: $this->request->post('uid');
        $fid = $sFid ?: $this->request->post('fid');
        $page = $sPage ?: $this->request->post('page');

        //获取用户身份
        $userTags = null;
        $user = $this->tool->getUserByUid($uid);
        if(!$user['adminid']){
            //获取用户标签
            $userTags = $this->tool->getUserTags($uid);
            foreach ($userTags as $k=>$userTag){
                $userTags[$k] = $userTag['tagid'];
            }
            if(!$userTags){
                return '用户无标签';
            }
        }
        //根据用户标签获取帖子
        $pagesData = $this->tool->getPages($fid, $uid, $page, $userTags);
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

    /**
     * @SWG\Post(
     *   path="page-threadSearch",
     *   tags={"帖子相关"},
     *   summary="主题搜索",
     *   description="主题搜索",
     *   operationId="threadSearch",
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *     @SWG\Parameter(name="fid", in="formData", description="群组ID", required=true, type="string"),
     *     @SWG\Parameter(name="uid", in="formData", description="用户ID", required=true, type="string"),
     *     @SWG\Parameter(name="keyword", in="formData", description="关键字", required=true, type="string"),
     *     @SWG\Response(response=200, description="{'state':{结果代码},'result':{返回结果}}"),
     * )
     */
    public function threadSearch() {
        $this->checkParam(['keyword', 'fid', 'uid']);
        $keyword = $this->request->post('keyword');
        $fid = $this->request->post('fid');
        $uid = $this->request->post('uid');

        //获取用户身份
        $userTags = null;
        $user = $this->tool->getUserByUid($uid);
        if(!$user['adminid']){
            //获取用户标签
            $userTags = $this->tool->getUserTags($uid);
            foreach ($userTags as $k=>$userTag){
                $userTags[$k] = $userTag['tagid'];
            }
            if(!$userTags){
                return '用户无标签';
            }
        }
        //根据用户标签获取帖子
        $pagesData = $this->tool->getPages($fid, $uid, null, $userTags, $keyword);
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