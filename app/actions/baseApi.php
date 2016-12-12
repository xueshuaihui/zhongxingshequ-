<?php
require_once API.'request.php';
use discuz\request;
/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="zte.rmbplus.com/app.php?action=",
 *     basePath="/",
 *     @SWG\Info(
 *         version="确定了，1.1",
 *         title="页面基础地址：app.php?show=",
 *         description="【1】圈子详情：circle-home [uid,fid] ;  【2】圈子成员：circle-member [fid] ; 【3】会员资料：member-details [uid] ; 【4】私信内容：message-pmc [uid, touid, page]; 【5】私信列表：message-pm [uid, page]; 【6】公告列表：message-pt [page]; 【7】公告详情：message-ptc [mid]; 【8】提醒列表：message-tips [uid,page]; 【9】帖子详情：page-pageList[fid, tid]; 【10】帖子列表[fid, uid, page]; 【11】我的帖子：page-my[uid, page]",
 *     ),
 * )
 */
class baseApi {
    protected $request;
    public function __construct() {
        $this->request = new request();
    }
    protected function checkParam ($params, $method = 'post', $use = false) {
        if(!defined('SHOW') || !SHOW || $use) {
            if (is_array($params)) {
                foreach ($params as $param) {
                    if (is_null($this->request->$method($param))) {
                        if($use){
                            exit('error');
                        }
                        return 10001;//缺少参数
                    }
                }
            } elseif (is_string($params)) {
                if (is_null($this->request->$method($params))) {
                    if($use){
                        exit('error');
                    }
                    return 10001;//缺少参数
                }
            }
        }
    }
}