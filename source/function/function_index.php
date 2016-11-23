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
        if(strlen($data[$k]['subject']) > 25){
            $data[$k]['subject'] = mb_substr($data[$k]['subject'], 0, 25,"utf-8").'...';
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
    }
    return pageFormat($pagnate, $pageId);
}

function getHDZL($bkId, $for = 1) {
    switch ($for){
        case 1:
            return C::t('forum_forum')->fetch_info_by_fid($bkId)['name'];
        break;
        case 2:
            $data = C::t('forum_forum')->fetch_all_by_fup($bkId);
            foreach ($data as $k => $v) {
                $data[$k]['icon'] = getHDZL($v['fid'], 3);
            }
            return $data;
        break;
        case 3:
            $url = C::t('forum_forumfield')->fetch_icon_by_fid($bkId);
            if(strpos($url, 'http') === false){
                return ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'attachment'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.$url;
            }else{
                return $url;
            }
        break;
        default: return false;
    }
}

function getExpertList ($subBk) {
    if(!is_array($subBk)) {
        return exit('error');
    }
    $fids = '';
    foreach ($subBk as $bk){
        $fids .= $bk['fid'].',';
    }
    $expertInfo = C::t('forum_forumfield')->get_all_username_by_fid(trim($fids, ','));
    $expertName = [];
    foreach ($expertInfo as $k=>$username) {
        $name = $username['moderators'];
        if($name){
            $temp = explode('	', $name);
            if(!in_array($temp[0], $expertName)){
                $expertName[$k] = $temp[0];
            }
        }
    }
     return C::t('common_member')->fetch_all_by_username($expertName);
}

function friendLink () {
    return C::t('common_friendlink')->fetch_all_by_displayorder();
}

function pageFormat($pageArr, $cur = 1) {
    $result = '';
    if($cur > 1) {
        $result .= '<li onclick="asyncLoad('.($cur-1).')" class="xsh_pages_Previous"></li>';
    }
    if(count($pageArr) > 10 && $cur > 4){
        $result .= '<li>...</li>';
    }
    foreach ($pageArr as $page) {
        if($page == $cur) {
            $result .= '<li class="xsh_pages_hot" onclick="asyncLoad('.$page.')">'.$page.'</li>';
        }else{
            $result .= '<li onclick="asyncLoad('.$page.')">'.$page.'</li>';
        }
    }
    if(count($pageArr) > 10 && (end($pageArr) - $cur) > 5){
        $result .= '<li>...</li>';
    }
    if($cur < count($pageArr)) {
        $result .= '<li onclick="asyncLoad('.($cur+1).')" class="xsh_pages_next"></li>';
    }
    return $result;
}