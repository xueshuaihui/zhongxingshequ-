<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
function dd($d){
    var_dump($d);
    exit;
}

function apiReturn($r, $d, $data = null) {
    echo json_encode(
        array(
            'state' => $r?1:0,
            'description' => $d,
            'data' => $data
        )
        ,true);
    exit;
}

function getInfo($sortId, $pageId, $perpage){
    $data =  C::t('forum_thread')->fetch_all_by_sortid($sortId, ($pageId-1)*$perpage, $perpage);
    foreach ($data as $k => $v){
        $data[$k]['tid'] = $v['tid'];
        $data[$k] = C::t('forum_post')->fetch_visiblepost_by_tid(0, $v['tid']);
        $data[$k]['message'] = preg_replace('/\[.*?\]/', '', $data[$k]['message']);
        preg_match_all('/(https|http):\/\/(.*?)(png|jpeg|gif|jpg)/i', $data[$k]['message'], $imgUrl);
        $data[$k]['message'] = preg_replace('/(https|http):\/\/(.*?)(png|jpeg|gif|jpg)/i','',$data[$k]['message']);
        $firstImg = $imgUrl[0][0];
        if(strlen($data[$k]['message']) > 80) {
            $data[$k]['message'] = mb_substr($data[$k]['message'],0,80,"utf-8").'...';
        }
        if(strlen($data[$k]['subject']) > 3*13){
            $data[$k]['subject'] = substr($data[$k]['subject'], 0, 3*13).'...';
        }
        if($firstImg) {
            $data[$k]['image'] = $firstImg;
        } else {
            $data[$k]['image'] = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'attachment'.DIRECTORY_SEPARATOR.'forum'.DIRECTORY_SEPARATOR.C::t('forum_threadimage')->fetch($v['tid'])['attachment'];
        }
        $data[$k]['views'] = $v['views'];
        $data[$k]['dateline'] = date('Y-m-d', $v['dateline']);
    }
    return $data;
}

function getPagnate($sortId, $pageId, $perpage){
    /*分页信息*/
    $itemCount = C::t('forum_thread')->count_all_by_sortid($sortId);
    $pageCount = ceil($itemCount / $perpage);
    $pagnate = [];
    if($pageCount > 1) {
        for ($i=1; $i<=$pageCount; $i++){
            array_push($pagnate, $i);
        }
        if($pageId != $pageCount) {
            array_push($pagnate, 'next');
        }
        if($pageId != 1) {
            array_unshift($pagnate, 'pre');
        }
    }
    return count($pagnate) > 0 ? $pagnate : 0;
}

function addDot ($arr) {
    if(count($arr) > 10) {
        foreach ($arr as $k=>$v){
            if($k == 9) $arr[$k] = '...';
            if($k > 10) unset($arr[$k]);
        }
    }
    return $arr;
}