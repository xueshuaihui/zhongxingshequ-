<?php
/**
 * Created by PhpStorm.
 * User: houruishuang
 * Date: 2016/11/26
 * Time: 下午12:29
 */

namespace discuz;

require_once INTER.'responseInterface.php';
use discuz\inter\responseInterface;
use Doctrine\Common\Annotations\FileCacheReader;

class response implements responseInterface{
    protected static $response = null;
    protected static $data;
    const SUCCESS = 10000;
    const FAIL = 00000;
    private static $msg = [
        00000 =>  '失败了，不解释',
        10000 =>  '成功',
        10001 =>  '缺少参数',
        10002 =>  '用户名不存在',
        10003 =>  '用户名或密码错误',
        10004 =>  '两次密码输入不一致',
        10005 =>  '用户名重复',
        10006 =>  '邮箱重复',
        10007 =>  '参数错误',
        10008 =>  '用户密码错误',
    ];
    public function __construct($data = '') {
        if(is_bool($data) && $data){
            self::$data = [
                'state' => self::SUCCESS,
                'result'=> self::$msg[self::SUCCESS]
            ];
        }elseif(is_array($data) && isset($data[1]) && is_numeric($data[1])) {
            self::$data = [
                'state' => $data[0],
                'result'  => $data[1]
            ];
        }elseif(is_bool($data)){
            $data = $data ? self::SUCCESS : self::FAIL;
            self::$data = [
                'state' => $data,
                'result'  => self::$msg[$data]
            ];
        }elseif(isset(self::$msg[$data])){
            self::$data = [
                'state' => $data,
                'result'  => self::$msg[$data]
            ];
        }else{
            self::$data = [
                'state' => self::SUCCESS,
                'result'  => $data
            ];
        }
    }

    public static function getResponse($data = '') {
        if(is_null(self::$response)){
            self::$response = new response($data);
        }
        return self::$response;
    }

    public function send () {
        header("Content-type: application/json");
        echo json_encode(self::$data, true);
    }

}