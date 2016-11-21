<?php


require_once './source/class/class_core.php';


$discuz = C::app();


$discuz->init();

$mod = 'new_index';
require_once libfile('group/'.$mod, 'module');