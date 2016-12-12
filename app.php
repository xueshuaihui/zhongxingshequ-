<?php
session_start();
define('IN_APP', 1);
define('__', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).__);
define('API', ROOT.'app'.__);
define('INTER', API.'interface'.__);
define('MODEL', API.'model'.__);
define('REPOSITORY', API.'repository'.__);
define('DOCUMENT', API.'document'.__);
define('LIB', API.'lib'.__);
$url = $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'];
define('BASEURL', $url);
function dd($d){
    var_dump($d); exit;
}
function encodeUrl($url){
    $url = BASEURL.__.$url;
    $url = str_replace('/', '##', $url);
    return 'zxbbs://jump/'.urlencode($url);
}
require_once API.'app.php';
\discuz\app::run();