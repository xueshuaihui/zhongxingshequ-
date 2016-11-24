<?php
require './source/class/class_core.php';


require './source/function/function_forum.php';


$modarray = array('ajax','announcement','attachment','forumdisplay',
    'group','image','index','medal','misc','modcp','notice','post','redirect',
    'relatekw','relatethread','rss','topicadmin','trade','viewthread','tag','collection','guide'
);

$modcachelist = array(
    'index'		=> array('announcements', 'onlinelist', 'forumlinks',
        'heats', 'historyposts', 'onlinerecord', 'userstats', 'diytemplatenameforum'),
    'forumdisplay'	=> array('smilies', 'announcements_forum', 'globalstick', 'forums',
        'onlinelist', 'forumstick', 'threadtable_info', 'threadtableids', 'stamps', 'diytemplatenameforum'),
    'viewthread'	=> array('smilies', 'smileytypes', 'forums', 'usergroups',
        'stamps', 'bbcodes', 'smilies',	'custominfo', 'groupicon', 'stamps',
        'threadtableids', 'threadtable_info', 'posttable_info', 'diytemplatenameforum'),
    'redirect'	=> array('threadtableids', 'threadtable_info', 'posttable_info'),
    'post'		=> array('bbcodes_display', 'bbcodes', 'smileycodes', 'smilies', 'smileytypes',
        'domainwhitelist', 'albumcategory'),
    'space'		=> array('fields_required', 'fields_optional', 'custominfo'),
    'group'		=> array('grouptype', 'diytemplatenamegroup'),
);

$mod = !in_array(C::app()->var['mod'], $modarray) ? 'forumdisplay' : C::app()->var['mod'];


define('CURMODULE', $mod);
$cachelist = array();
if(isset($modcachelist[CURMODULE])) {
    $cachelist = $modcachelist[CURMODULE];

    $cachelist[] = 'plugin';
    $cachelist[] = 'pluginlanguage_system';
}
if(C::app()->var['mod'] == 'group') {
    $_G['basescript'] = 'group';
}

C::app()->cachelist = $cachelist;
C::app()->init();


loadforum();


set_rssauth();


runhooks();



$navtitle = str_replace('{bbname}', $_G['setting']['bbname'], $_G['setting']['seotitle']['forum']);
$_G['setting']['threadhidethreshold'] = 1;

if ($_GET['action'] == 'group_list') {
    $mod = 'new_index';
    require_once libfile('group/'.$mod, 'module');
} else {
    
    $mod = 'new_index';
    require_once libfile('group/'.$mod, 'module');
    
    require DISCUZ_ROOT.'./source/module/forum/forum_'.'forumdisplay'.'.php';
}
