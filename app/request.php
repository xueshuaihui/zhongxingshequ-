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

    public function session($param = '') {
        if($param == ''){
            return $_SESSION;
        } elseif (strpos($param, '.')){
            $params = explode('.', $param);
            $return = $_SESSION;
            foreach ($params as $item){
                if(isset($return[$item])){
                    $return = $return[$item];
                }else{
                    return null;
                }
            }
            return $return;
        } else {
            return isset($_SESSION[$param]) ? $_SESSION[$param] : null;
        }
    }

    public function setCode($k, $v = null) {
        $sourceData = '';
        if(file_exists($path = 'data'.DIRECTORY_SEPARATOR.'code.json')){
            $sourceData = file_get_contents($path);
        }
        $sourceArr = json_decode($sourceData, true);
        if(is_string($k)){
            $sourceArr[$k] = $v;
        }elseif(is_array($k)){
            foreach ($k as $key=>$value){
                $sourceArr[$key] = $value;
            }
        }
        $resultData = json_encode($sourceArr);
        file_put_contents($path, $resultData);
    }

    public function checkCode($key, $code) {
        if(!file_exists($path = 'data'.DIRECTORY_SEPARATOR.'code.json')){
            return false;
        }
        $sourceData = file_get_contents($path);
        $sourceArr = json_decode($sourceData, true);
        if(strpos($key, '.') === false){
            if($sourceArr[$key]['code'] == $code && $sourceArr[$key]['expire'] < time()){
                unset($sourceArr[$key]);
                $resultData = json_encode($sourceArr);
                file_put_contents($path, $resultData);
                return true;
            }else{
                return false;
            }
        }else{
            $k = explode('.', $key);
            $return = '';
            $root = '';
            foreach ($k as $sub=>$subKey){
                if($sub == 0){
                    $root = $subKey;
                    $return = $sourceArr[$root];
                }else{
                    $return = $return[$subKey];
                }
            }
            if($return['token'] == $code && $return['expire'] > time()){
                unset($sourceArr[$root]);
                $resultData = json_encode($sourceArr);
                file_put_contents($path, $resultData);
                return true;
            }else{
                return false;
            }
        }
    }

    public function setSession($k, $v = null) {
        if(is_string($k)){
            $_SESSION[$k] = $v;
        }elseif(is_array($k)){
            foreach ($k as $key => $value){
                $this->setSession($key, $value);
            }
        }
    }

    public function hasFile() {
        if(isset($_FILES) && count($_FILES) > 0){
            foreach ($_FILES as $file){
                if($file['size'] > 0){
                    return true;
                }
            }
        }
        return false;
    }

    public function file($name = null) {
        if(is_null($name)){
            return $_FILES;
        }
        return $_FILES[$name]['size'] > 0 ? $_FILES[$name] : null;
    }
}