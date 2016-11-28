<?php
session_start();
define('ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('API', ROOT.'app'.DIRECTORY_SEPARATOR);
define('INTER', API.'interface'.DIRECTORY_SEPARATOR);
define('MODEL', API.'model'.DIRECTORY_SEPARATOR);
define('RESPOSITORY', API.'repository'.DIRECTORY_SEPARATOR);
define('DOCUMENT', API.'document'.DIRECTORY_SEPARATOR);
function dd($d){
    var_dump($d); exit;
}
require_once API.'app.php';
\discuz\app::run();