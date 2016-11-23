<?php
define('APPTYPEID', 250);
define('CURSCRIPT', 'ztindex');

require './source/class/class_core.php';

C::app()->init();

$navtitle = str_replace('{bbname}', $_G['setting']['bbname'], $_G['setting']['seotitle']['portal']);
$_G['disabledwidthauto'] = 1;
if(isset($_GET['api'])) $isApi = true;
if(isset($_GET['t'])) $param['threadTypeId'] = $_GET['t'];
if(isset($_GET['p'])) $param['pageId'] = $_GET['p'];
require_once libfile('index/index', 'module');
