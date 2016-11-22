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

//======帖子列表==============

$_G['forum_colorarray'] = array('', '#EE1B2E', '#EE5023', '#996600', '#3C9D40', '#2897C5', '#2B65B7', '#8F2A90', '#EC1282');

//筛选条件
$filterarr = array();
$filterarr['inforum'] = array(36, 37, 38, 39);

$pre_page_count = 20;

$threadtableids = !empty($_G['cache']['threadtableids']) ? $_G['cache']['threadtableids'] : array();
$tableid = $_GET['archiveid'] && in_array($_GET['archiveid'], $threadtableids) ? intval($_GET['archiveid']) : 0;

$offset = ($page - 1) * $pre_page_count;
$_GET['orderby'] = isset($_G['cache']['forums'][$_G['fid']]['orderby']) ? $_G['cache']['forums'][$_G['fid']]['orderby'] : 'lastpost';
$_GET['ascdesc'] = isset($_G['cache']['forums'][$_G['fid']]['ascdesc']) ? $_G['cache']['forums'][$_G['fid']]['ascdesc'] : 'DESC';
$_order = "displayorder DESC, $_GET[orderby] $_GET[ascdesc]";

$threadlist = array();
$_G['forum_threadcount'] = C::t('forum_thread')->count_search($filterarr, $tableid);
$threadlist = array_merge($threadlist, C::t('forum_thread')->fetch_all_search($filterarr, $tableid, $offset, $pre_page_count, $_order, '', ''));



$threadindex = 0;
foreach($threadlist as $thread) {
    $thread['allreplies'] = $thread['replies'] + $thread['comments'];
    $thread['ordertype'] = getstatus($thread['status'], 4);
    if($_G['forum']['picstyle'] && empty($_G['cookie']['forumdefstyle'])) {
        if($thread['fid'] != $_G['fid'] && empty($thread['cover'])) {
            continue;
        }
        $thread['coverpath'] = getthreadcover($thread['tid'], $thread['cover']);
        $thread['cover'] = abs($thread['cover']);
    }
    $thread['forumstick'] = in_array($thread['tid'], $forumstickytids);
    $thread['related_group'] = 0;
    if($_G['forum']['relatedgroup'] && $thread['fid'] != $_G['fid']) {
        if($thread['closed'] > 1) continue;
        $thread['related_group'] = 1;
        $grouptids[] = $thread['tid'];
    }
    $thread['lastposterenc'] = rawurlencode($thread['lastposter']);
    if($thread['typeid'] && !empty($_G['forum']['threadtypes']['prefix']) && isset($_G['forum']['threadtypes']['types'][$thread['typeid']])) {
        if($_G['forum']['threadtypes']['prefix'] == 1) {
            $thread['typehtml'] = '<em>[<a href="forum.php?mod=forumdisplay&fid='.$_G['fid'].'&amp;filter=typeid&amp;typeid='.$thread['typeid'].'">'.$_G['forum']['threadtypes']['types'][$thread['typeid']].'</a>]</em>';
        } elseif($_G['forum']['threadtypes']['icons'][$thread['typeid']] && $_G['forum']['threadtypes']['prefix'] == 2) {
            $thread['typehtml'] = '<em><a title="'.$_G['forum']['threadtypes']['types'][$thread['typeid']].'" href="forum.php?mod=forumdisplay&fid='.$_G['fid'].'&amp;filter=typeid&amp;typeid='.$thread['typeid'].'">'.'<img style="vertical-align: middle;padding-right:4px;" src="'.$_G['forum']['threadtypes']['icons'][$thread['typeid']].'" alt="'.$_G['forum']['threadtypes']['types'][$thread['typeid']].'" /></a></em>';
        }
        $thread['typename'] = $_G['forum']['threadtypes']['types'][$thread['typeid']];
    } else {
        $thread['typename'] = $thread['typehtml'] = '';
    }

    $thread['sorthtml'] = $thread['sortid'] && !empty($_G['forum']['threadsorts']['prefix']) && isset($_G['forum']['threadsorts']['types'][$thread['sortid']]) ?
    '<em>[<a href="forum.php?mod=forumdisplay&fid='.$_G['fid'].'&amp;filter=sortid&amp;sortid='.$thread['sortid'].'">'.$_G['forum']['threadsorts']['types'][$thread['sortid']].'</a>]</em>' : '';
    $thread['multipage'] = '';
    $topicposts = $thread['special'] ? $thread['replies'] : $thread['replies'] + 1;
    $multipate_archive = $_GET['archiveid'] && in_array($_GET['archiveid'], $threadtableids) ? "archiveid={$_GET['archiveid']}" : '';
    if($topicposts > $_G['ppp']) {
        $pagelinks = '';
        $thread['pages'] = ceil($topicposts / $_G['ppp']);
        $realtid = $_G['forum']['status'] != 3 && $thread['isgroup'] == 1 ? $thread['closed'] : $thread['tid'];
        for($i = 2; $i <= 6 && $i <= $thread['pages']; $i++) {
            $pagelinks .= "<a href=\"forum.php?mod=viewthread&tid=$realtid&amp;".(!empty($multipate_archive) ? "$multipate_archive&amp;" : '')."extra=$extra&amp;page=$i\">$i</a>";
        }
        if($thread['pages'] > 6) {
            $pagelinks .= "..<a href=\"forum.php?mod=viewthread&tid=$realtid&amp;".(!empty($multipate_archive) ? "$multipate_archive&amp;" : '')."extra=$extra&amp;page=$thread[pages]\">$thread[pages]</a>";
        }
        $thread['multipage'] = '&nbsp;...'.$pagelinks;
    }

    if($thread['highlight']) {
        $string = sprintf('%02d', $thread['highlight']);
        $stylestr = sprintf('%03b', $string[0]);

        $thread['highlight'] = ' style="';
        $thread['highlight'] .= $stylestr[0] ? 'font-weight: bold;' : '';
        $thread['highlight'] .= $stylestr[1] ? 'font-style: italic;' : '';
        $thread['highlight'] .= $stylestr[2] ? 'text-decoration: underline;' : '';
        $thread['highlight'] .= $string[1] ? 'color: '.$_G['forum_colorarray'][$string[1]].';' : '';
        if($thread['bgcolor']) {
            $thread['highlight'] .= "background-color: $thread[bgcolor];";
        }
        $thread['highlight'] .= '"';
    } else {
        $thread['highlight'] = '';
    }

    $thread['recommendicon'] = '';
    if(!empty($_G['setting']['recommendthread']['status']) && $thread['recommends']) {
        foreach($_G['setting']['recommendthread']['iconlevels'] as $k => $i) {
            if($thread['recommends'] > $i) {
                $thread['recommendicon'] = $k+1;
                break;
            }
        }
    }

    $thread['moved'] = $thread['heatlevel'] = $thread['new'] = 0;
    if($_G['forum']['status'] != 3 && ($thread['closed'] || ($_G['forum']['autoclose'] && $thread['fid'] == $_G['fid'] && TIMESTAMP - $thread[$closedby] > $_G['forum']['autoclose']))) {
        if($thread['isgroup'] == 1) {
            $thread['folder'] = 'common';
            $grouptids[] = $thread['closed'];
        } else {
            if($thread['closed'] > 1) {
                $thread['moved'] = $thread['tid'];
                $thread['allreplies'] = $thread['replies'] = '-';
                $thread['views'] = '-';
            }
            $thread['folder'] = 'lock';
        }
    } elseif($_G['forum']['status'] == 3 && $thread['closed'] == 1) {
        $thread['folder'] = 'lock';
    } else {
        $thread['folder'] = 'common';
        $thread['weeknew'] = TIMESTAMP - 604800 <= $thread['dbdateline'];
        if($thread['allreplies'] > $thread['views']) {
            $thread['views'] = $thread['allreplies'];
        }
        if($_G['setting']['heatthread']['iconlevels']) {
            foreach($_G['setting']['heatthread']['iconlevels'] as $k => $i) {
                if($thread['heats'] > $i) {
                    $thread['heatlevel'] = $k + 1;
                    break;
                }
            }
        }
    }
    $thread['icontid'] = $thread['forumstick'] || !$thread['moved'] && $thread['isgroup'] != 1 ? $thread['tid'] : $thread['closed'];
    if(!$thread['forumstick'] && ($thread['isgroup'] == 1 || $thread['fid'] != $_G['fid'])) {
        $thread['icontid'] = $thread['closed'] > 1 ? $thread['closed'] : $thread['tid'];
    }
    $thread['istoday'] = $thread['dateline'] > $todaytime ? 1 : 0;
    $thread['dbdateline'] = $thread['dateline'];
    $thread['dateline'] = dgmdate($thread['dateline'], 'u', '9999', getglobal('setting/dateformat'));
    $thread['dblastpost'] = $thread['lastpost'];
    $thread['lastpost'] = dgmdate($thread['lastpost'], 'u');
    $thread['hidden'] = $_G['setting']['threadhidethreshold'] && $thread['hidden'] >= $_G['setting']['threadhidethreshold'] || in_array($thread['tid'], $thide);
    if($thread['hidden']) {
        $_G['hiddenexists']++;
    }

    if(in_array($thread['displayorder'], array(1, 2, 3, 4))) {
        $thread['id'] = 'stickthread_'.$thread['tid'];
        $separatepos++;
    } else {
        $thread['id'] = 'normalthread_'.$thread['tid'];
        if($thread['folder'] == 'common' && $thread['dblastpost'] >= $forumlastvisit || !$forumlastvisit) {
            $thread['new'] = 1;
            $thread['folder'] = 'new';
            $thread['weeknew'] = TIMESTAMP - 604800 <= $thread['dbdateline'];
        }
        $_G['showrows']++;
    }
    if(isset($_G['setting']['verify']['enabled']) && $_G['setting']['verify']['enabled']) {
        $verifyuids[$thread['authorid']] = $thread['authorid'];
    }
    $authorids[$thread['authorid']] = $thread['authorid'];
    $thread['mobile'] = base_convert(getstatus($thread['status'], 13).getstatus($thread['status'], 12).getstatus($thread['status'], 11), 2, 10);
    $thread['rushreply'] = getstatus($thread['status'], 3);
    if($thread['rushreply']) {
        $rushtids[$thread['tid']] = $thread['tid'];
    }
    $threadids[$threadindex] = $thread['tid'];
    $_G['forum_threadlist'][$threadindex] = $thread;
    $threadindex++;

}


include_once template('group/group_new_index');