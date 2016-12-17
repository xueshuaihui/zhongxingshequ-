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
        00000 =>  '服务器错误',
        10000 =>  '成功',
        10001 =>  '缺少参数',
        10002 =>  '用户名不存在',
        10003 =>  '用户名或密码错误',
        10004 =>  '两次密码输入不一致',
        10005 =>  '用户名重复',
        10006 =>  '邮箱重复',
        10007 =>  '参数错误',
        10008 =>  '用户密码错误',
        10009 =>  '该手机号并没有绑定用户哦',
        10010 =>  '验证码不正确',
        10011 =>  '该手机号已被绑定账号',
        10012 =>  '该用户还没有加入圈子哦',
        10013 =>  '该用户还没有通过审核呢',
        10014 =>  '圈子创建者有误',
        10015 =>  '该用户不是管理员',
        10016 =>  '创建者不能退出圈子',
        10017 =>  '图片只允许jpg,jpeg,png',
        10018 =>  '图片上传错误',
        10019 =>  '邀请码不可用',
        10020 =>  '帖子标题不能为空',
        10021 =>  '帖子内容不能为空',
        10022 =>  '该用户已经加入或者申请了',
        10023 =>  '加入成功！',
        10024 =>  '不能删除自己',
    ];
    public function __construct($data = '') {
        if(is_bool($data)){
            $res = $data?self::SUCCESS:self::FAIL;
            self::$data = [
                'state' => $res,
                'msg'   => self::$msg[$res],
                'result'=> null
            ];
        }elseif(array_key_exists($data, self::$msg)){
            self::$data = [
                'state' => $data,
                'msg'   => self::$msg[$data],
                'result'=> null
            ];
        }else{
            self::$data = [
                'state' => self::SUCCESS,
                'msg'   => self::$msg[self::SUCCESS],
                'result'=> $data
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
        echo json_encode(self::$data);
    }

}