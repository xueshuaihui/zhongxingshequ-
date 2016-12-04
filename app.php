<?php
session_start();
define('__', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).__);
define('API', ROOT.'app'.__);
define('INTER', API.'interface'.__);
define('MODEL', API.'model'.__);
define('RESPOSITORY', API.'repository'.__);
define('DOCUMENT', API.'document'.__);
define('LIB', API.'lib'.__);
$url = $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'];
define('BASEURL', $url);
function dd($d){
    var_dump($d); exit;
}
require_once API.'app.php';
\discuz\app::run();