<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once libfile('function/index');

if(isset($isApi) && $isApi){
    if(!isset($param['threadTypeId']) || !isset($param['pageId'])){
        apiReturn(0, '缺少参数');
    }
    $threadTypeId = $param['threadTypeId'];
    $pageId = $param['pageId'];
    /*获取分类信息下的信息流*/
    $infoFlow = getInfo($threadTypeId, $pageId);
    $pagnate = getPagnate($threadTypeId, $pageId);
    apiReturn(1, '获取成功', ['data'=>$infoFlow, 'pagnate'=>$pagnate]);
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


/*获取分类信息*/
$threadType = C::t('forum_threadtype')->fetch_all_for_order();
/*获取专家排行*/
/*获取分类互动专题*/
/*获取友情链接*/
/*获取版权信息*/

if(isset($_G['makehtml'])){
    helper_makehtml::index_index();
}
$banner = explode(',', $_G['setting']['banner_order']);

$curType = isset($threadTypeId)?$threadTypeId:$threadType[0]['typeid'];
$curPage = isset($pageId)?$pageId:1;
$infoFlow = getInfo($curType, $curPage, $_G['setting']['index_list_count']);
$pagnate = ['pre',1,2,3,4,5,6,7,8,9,10,11,'next'];//getPagnate($curType, $curPage);
$pagnate = addDot($pagnate);
include_once template('index/index');
?>