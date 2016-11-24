<?php

//我加入的圈子、管理的圈子、全部圈子
require_once libfile('function/group');

$perpage = 20;
$page = intval($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $perpage;

//加入的圈子列表
$ismanager = 2;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
//$multipage = multi($num, $perpage, $page, 'group.php?mod=my&view='.$view);
$grouplist_join = mygrouplist($_G['uid'], 'lastupdate', array(), 10000, 0, $ismanager);

//管理的圈子列表
$ismanager = 1;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
//$multipage = multi($num, $perpage, $page, 'group.php?mod=my&view='.$view);
$grouplist_manage = mygrouplist($_G['uid'], 'lastupdate', array(), 10000, 0, $ismanager);

//全部圈子列表
$ismanager = 0;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
//$multipage = multi($num, $perpage, $page, 'group.php?mod=my&view='.$view);
$grouplist_all = mygrouplist($_G['uid'], 'lastupdate', array(), 10000, 0, $ismanager);

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
        $group['href'] = 'http://www.sina.com.cn';
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