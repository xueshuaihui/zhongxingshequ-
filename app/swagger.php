<?php
if(isset($_GET['re'])){
    require_once "vendor/autoload.php";
    $path = 'actions';
    $newJson = \Swagger\scan($path);
    $jsonUrl = 'document/swagger.json';
    file_put_contents($jsonUrl, $newJson);
}
Header('Location: /app/document');