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
 *         description="
页面地址：<br/>
【1】圈子详情：circle-home [uid,fid] ;  <br/>
【2】圈子成员：circle-member [fid, uid] ;<br/>
【3】会员资料：member-details [uid] ;<br/>
【4】私信内容：message-pmc [uid, touid, page];<br/>
【5】私信列表：message-pm [uid, page]; <br/>
【6】公告列表：message-pt [page]; <br/>
【7】公告详情：message-ptc [mid]; <br/>
【8】提醒列表：message-tips [uid,page]; <br/>
【9】帖子详情：page-pageContent[fid, tid, uid]; <br/>
【10】帖子列表：page-pageList[fid, tid, page, uid];<br/>
【11】我的帖子：page-my[uid, page]; <br/><br/>
=================================<br/><br/>
【1】zxbbs://post/new/{fid} 发帖<br/>
【2】zxbbs://circle/transfer 转让圈子<br/>
【3】zxbbs://circle/invite   邀请好友进圈子<br/>
【4】zxbbs://circle/defriend 删除圈子成员<br/>
【5】zxbbs://circle/modifyName 编辑圈子名称<br/>
【6】zxbbs://circle/modifyDesc  编辑圈子简介<br/>
=================================<br/><br/>
【1】弹窗：zxbbs://alert/content_string<br/>
【2】跳转：zxbbs://jump/url/xurl为目标url的urlencode字符串，/替换为##
=================================<br/><br/>
stamp:-1=>无 0=>精华1=>热帖2=>美图3=>优秀4=>置顶5=>推荐6=>原创7=>版主推荐8=>爆料19=>编辑采用
",
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