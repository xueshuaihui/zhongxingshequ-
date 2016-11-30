<?php
//我加入的圈子、管理的圈子、全部圈子
require_once libfile('function/group');

global $_G;
//加入的圈子列表
$ismanager = 2;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
$grouplist_join = mygrouplist($_G['uid'], 'lastupdate', array(), 10000, 0, $ismanager);

//管理的圈子列表
$ismanager = 1;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
$grouplist_manage = mygrouplist($_G['uid'], 'lastupdate', array(), 10000, 0, $ismanager);

//全部圈子列表
$ismanager = 0;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
//$grouplist_all = mygrouplist($_G['uid'], 'lastupdate', array(), 10000, 0, $ismanager);
$grouplist_all = grouplist('displayorder', array(), 10000);

//计算圈子成员数
// $group_user_count_arr = array();
// foreach ($grouplist_all as $group) {
//     $group_user_count_arr[$group['fid']] = C::t('forum_groupuser')->fetch_count_by_fid($group['fid']);
// }

//====================加入、管理、全部圈子======================
//参数action 为group_list
//参数grouptype join我加入的圈子，manage我管理的圈子，all全部的圈子
if ($_GET['action'] == 'group_list') {
    
    $group_list = array();
    switch ($_GET['grouptype']) {
        case 'join':
            {
                $group_list = $grouplist_join;
            }
            break;
        case 'manage':
            {
                $group_list = $grouplist_manage;
            }
            break;
        default:
            {
                $group_list = $grouplist_all;
            }
            break;
    }
    
    foreach ($group_list as &$group) {
        $group['href'] = 'forum.php?mod=forumdisplay&action=list&fid='.$group['fid'];
    }
    
    if (empty($group_list)) {
        display_json($group_list, 10001, '没有相应数据');
    } else {
        display_json($group_list, 10000);
    }
    
}

$grouplist_join = count($grouplist_join) ? array_slice($grouplist_join, 0, 4) : array();
$grouplist_manage = count($grouplist_manage) ? array_slice($grouplist_manage, 0, 4) : array();
$grouplist_all = count($grouplist_all) ? array_slice($grouplist_all, 0, 4) : array();

//热门圈子
$topgrouplist = grouplist('activity', array('f.commoncredits', 'ff.membernum', 'ff.icon'), 10);
//推荐圈子
$setting = &$_G['setting'];
$group_recommend = $setting['group_recommend'] ? dunserialize($setting['group_recommend']) : '';
//=============================圈子加强功能==================
//获取当前用户的用户标签
$user_tag_list = C::t('common_tagitem')->select(0, $_G['uid'], 'uid');
$user_tag_ids = array();
foreach ($user_tag_list as $user_tag) {
    $user_tag_ids[] = $user_tag['tagid'];
}
if ($_G['setting']['grouppowerpluginidisopen'] && $_G['adminid'] != 1) {
    foreach ($group_recommend as &$group) {
        $group_tag_list = C::t('common_tagitem')->select(0, $group['fid'], 'groupid');
        $group_tag_ids = array();
        foreach ($group_tag_list as $group_tag) {
            $group_tag_ids[] = $group_tag['tagid'];
        }

        if ($group_tag_ids && $user_tag_ids && !count(array_intersect($group_tag_ids, $user_tag_ids))) {
            unset($group);
        }
    }
    
}
//=============================圈子加强功能====================

$group_id_recommend = array_keys($group_recommend);

function display_json ($data = array(), $code = 10000, $error_msg = '') {
    $arr = ['code' => $code];
    if ($arr['code'] == 10000) {
        $arr['data'] = $data;
    } else {
        $arr['error_msg'] = $error_msg;
    }
    echo json_encode($arr);
    exit();
}


//include_once template('group/group_new_index');
