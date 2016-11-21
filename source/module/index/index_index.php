<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
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

if(isset($_G['makehtml'])){
    helper_makehtml::index_index();
}
$banner = explode(',', $_G['setting']['banner_order']);
include_once template('index/index');
?>