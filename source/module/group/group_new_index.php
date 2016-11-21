<?php

//我加入的圈子、管理的圈子、全部圈子
require_once libfile('function/group');

$view = $_GET['view'] && in_array($_GET['view'], array('manager', 'join', 'groupthread', 'mythread')) ? $_GET['view'] : 'groupthread';
$actives = array('manager' => '', 'join' => '', 'groupthread' => '', 'mythread' => '');
$actives[$view] = ' class="a"';

$perpage = 20;
$page = intval($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $perpage;

//加入的圈子列表
$ismanager = 2;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
$multipage = multi($num, $perpage, $page, 'group.php?mod=my&view='.$view);
$grouplist_join = mygrouplist($_G['uid'], 'lastupdate', array(), $perpage, $start, $ismanager);
$grouplist_join = count($grouplist_join) ? array_slice($grouplist_join, 0, 4) : array();
//管理的圈子列表
$ismanager = 1;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
$multipage = multi($num, $perpage, $page, 'group.php?mod=my&view='.$view);
$grouplist_manage = mygrouplist($_G['uid'], 'lastupdate', array(), $perpage, $start, $ismanager);
$grouplist_manage = count($grouplist_manage) ? array_slice($grouplist_manage, 0, 4) : array();
//全部圈子列表
$ismanager = 0;
$num = mygrouplist($_G['uid'], 'lastupdate', array('f.name', 'ff.icon'), 0, 0, $ismanager, 1);
$multipage = multi($num, $perpage, $page, 'group.php?mod=my&view='.$view);
$grouplist_all = mygrouplist($_G['uid'], 'lastupdate', array(), $perpage, $start, $ismanager);
$grouplist_join = count($grouplist_all) ? array_slice($grouplist_all, 0, 4) : array();

$usercount = C::t('forum_groupuser')->fetch_count_by_fid(36);

//计算圈子成员数
$group_user_count_arr = array();
foreach ($grouplist_all as $group) {
    $group_user_count_arr[$group['fid']] = C::t('forum_groupuser')->fetch_count_by_fid($group['fid']);
}


include_once template('group/group_new_index');