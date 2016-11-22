<?php
define('APPTYPEID', 250);
define('CURSCRIPT', 'ztindex');

require './source/class/class_core.php';

C::app()->init();

$navtitle = str_replace('{bbname}', $_G['setting']['bbname'], $_G['setting']['seotitle']['portal']);
$_G['disabledwidthauto'] = 1;
if(isset($_POST['api'])) $isApi = true;
if(isset($_POST['t'])) $param['threadTypeId'] = $_POST['t'];
if(isset($_POST['p'])) $param['pageId'] = $_POST['p'];
require_once libfile('index/index', 'module');
