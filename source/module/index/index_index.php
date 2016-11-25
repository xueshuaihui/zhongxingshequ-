<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once libfile('function/index');

if(isset($isApi) && $isApi){
    if($get == 'info'){
        if(!isset($param['threadTypeId']) || !isset($param['pageId'])){
            apiReturn(0, '缺少参数');
        }
        $threadTypeId = $param['threadTypeId'];
        $pageId = $param['pageId'];
        /*获取分类信息下的信息流*/
        $infoFlow = getInfo($threadTypeId, $pageId, $_G['setting']['index_list_count']);
        $pagnate = getPagnate($threadTypeId, $pageId, $_G['setting']['index_list_count']);
        apiReturn(1, '获取成功', ['data'=>$infoFlow, 'pagnate'=>$pagnate]);
    } elseif ($get == 'thread') {
        $header = getHDZL($param['tid'], 1);
        $data = getHDZL($param['tid'], 2);
        apiReturn(1, '获取成功', ['header'=>$header, 'experts'=>$data]);
    } elseif ($get == 'post') {
        require_once libfile('function/forum');
        $newthread = array(
            'fid' => $param['tid'],
            'posttableid' => 0,
            'readperm' => 0,
            'sortid' => 0,
            'author' => $_G['username'],
            'authorid' => $_G['uid'],
            'subject' => '专家提问：',
            'dateline' => getglobal('timestamp'),
            'lastpost' => getglobal('timestamp'),
            'lastposter' => $_G['username'],
            'status' => 32
        );
        //主题
        $ztid = C::t('forum_thread')->insert($newthread, true);
        $data = array(
            'fid' => $param['tid'],
            'first' => '0',
            'tid' => $ztid,
            'author' => $_G['username'],
            'authorid' => $_G['uid'],
            'subject' => '专家提问：',
            'dateline' => getglobal('timestamp'),
            'message' => $param['content'],
            'useip' => $_G['clientip']?:getglobal('clientip'),
            'port' => $_G['remoteport']?:getglobal('remoteport'),
        );
        //帖子
        $pid = insertpost($data);
        if($pid){
            $banzhu_ids = C::t('forum_moderator')->fetch_all_by_fid($param['tid']);
            sendMessageToIds($banzhu_ids, $_G['uid'], $_G['username'], $ztid, $param['tid'], $pid);
            apiReturn(1);
        }
    }

    apiReturn(0, '完蛋玩意');
}
/*页面配置*/
list($navtitle, $metadescription, $metakeywords) = get_seosetting('index');
if(!$navtitle) {
    $navtitle = $_G['setting']['navs'][1]['navname'];
    $nobbname = false;
} else {
    $nobbname = true;
}
if(!$metakeywords) {
    $metakeywords = $_G['setting']['navs'][1]['navname'];
}
if(!$metadescription) {
    $metadescription = $_G['setting']['navs'][1]['navname'];
}


$threadType = C::t('forum_threadtype')->fetch_all_for_order();

if(isset($_G['makehtml'])){
    helper_makehtml::index_index();
}
$banner = explode(',', $_G['setting']['banner_order']);

$hdzl = getHDZL($_G['setting']['index_hdzl'], 1);
$subBk = getHDZL($_G['setting']['index_hdzl'], 2);

$expertList = getExpertList($subBk);

$curType = isset($threadTypeId)?$threadTypeId:$threadType[0]['typeid'];
$curPage = 1;
$infoFlow = getInfo($curType, $curPage, $_G['setting']['index_list_count']);
$page = getPagnate($curType, $curPage, $_G['setting']['index_list_count']);
include_once template('index/index');
?>