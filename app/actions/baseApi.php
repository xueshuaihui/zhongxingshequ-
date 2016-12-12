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
 *         title="这是中心API，你没搞错吧",
 *         description="访问本域名是显示文档，访问swagger.php?re=1则刷新",
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