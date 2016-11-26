<?php

define('APPTYPEID', 250);
define('CURSCRIPT', 'ztindex');

require './source/class/class_core.php';

C::app()->init();

$navtitle = str_replace('{bbname}', $_G['setting']['bbname'], $_G['setting']['seotitle']['portal']);
$_G['disabledwidthauto'] = 1;
if(isset($_GET['api'])) $isApi = true;
if(isset($_G['setting']['index_qrcode_imgs'])){
    $indexs = explode(',', $_G['setting']['index_qrcode_imgs']);
    foreach ($indexs as $k=>$index) {
        $qrcodes[$k] = $_G['setting'][$index];
    }
}

if(isset($_GET['t']) && isset($_GET['p'])) {
    $param['threadTypeId'] = $_GET['t'];
    $param['pageId'] = $_GET['p'];
    $get = 'info';
}elseif(isset($_GET['thread'])){
    $get = 'thread';
    $param['tid'] = $_GET['thread'];
}elseif(isset($_GET['post']) && $_GET['post'] ==1){
    $get = 'post';
    $param['tid'] = $_POST['id'];
    $param['content'] = $_POST['text'];
}
require_once libfile('index/index', 'module');
