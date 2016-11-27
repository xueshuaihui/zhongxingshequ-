<?php
/**
 * Created by PhpStorm.
 * User: houruishuang
 * Date: 2016/11/26
 * Time: 下午12:29
 */
namespace discuz;

require_once INTER.DIRECTORY_SEPARATOR.'requestInterface.php';

use discuz\inter\requestInterface;

class request implements requestInterface{
    protected static $request = null;
    public static function getRequest() {
        if(is_null(self::$request)){
            self::$request = new request();
        }
        return self::$request;
    }

    public function get($param = '') {
        if($param == ''){
            return $_GET;
        } elseif (strpos($param, '.')){
            $params = explode('.', $param);
            $return = $_GET;
            foreach ($params as $item){
                if(isset($return[$item])){
                    $return = $return[$item];
                }else{
                    return null;
                }
            }
            return $return;
        } else {
            return isset($_GET[$param]) ? $_GET[$param] : null;
        }
    }

    public function post($param = '') {
        if($param == ''){
            return $_POST;
        } elseif (strpos($param, '.')){
            $params = explode('.', $param);
            $return = $_POST;
            foreach ($params as $item){
                if(isset($return[$item])){
                    $return = $return[$item];
                }else{
                    return null;
                }
            }
            return $return;
        } else {
            return isset($_POST[$param]) ? $_POST[$param] : null;
        }
    }
}