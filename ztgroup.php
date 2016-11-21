<?php


require_once './source/class/class_core.php';


$discuz = C::app();

// $cachelist = array('magic','userapp','usergroups', 'diytemplatenamehome');
// $discuz->cachelist = $cachelist;
$discuz->init();

// $space = array();

// $mod = getgpc('mod');
// if(!in_array($mod, array('space', 'spacecp', 'misc', 'magic', 'editor', 'invite', 'task', 'medal', 'rss', 'follow'))) {
//     $mod = 'space';
//     $_GET['do'] = 'home';
// }

// if($mod == 'space' && ((empty($_GET['do']) || $_GET['do'] == 'index') && ($_G['inajax']))) {
//     $_GET['do'] = 'profile';
// }
// $curmod = !empty($_G['setting']['followstatus']) && (empty($_GET['diy']) && empty($_GET['do']) && $mod == 'space' || $_GET['do'] == 'follow') ? 'follow' : $mod;
// define('CURMODULE', $curmod);
// runhooks($_GET['do'] == 'profile' && $_G['inajax'] ? 'card' : $_GET['do']);


$mod = 'new_index';
require_once libfile('group/'.$mod, 'module');