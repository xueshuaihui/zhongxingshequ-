<?php
define('APPTYPEID', 250);
define('CURSCRIPT', 'ztindex');

require './source/class/class_core.php';

C::app()->init();

$navtitle = str_replace('{bbname}', $_G['setting']['bbname'], $_G['setting']['seotitle']['portal']);
$_G['disabledwidthauto'] = 1;
require_once libfile('index/index', 'module');
