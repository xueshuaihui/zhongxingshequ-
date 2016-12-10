<?php

class smsClass
{
    /**
     *  云之讯REST API版本号。当前版本号为：2014-06-30
     */
    const SoftVersion = "2014-06-30";
    /**
     * API请求地址
     */
    const BaseUrl = "https://api.ucpaas.com/";
    /**
     * @var string
     * 开发者账号ID。由32个英文字母和阿拉伯数字组成的开发者账号唯一标识符。
     */
    private $accountSid;
    /**
     * @var string
     * 开发者账号TOKEN
     */
    private $token;
    /**
     * @var string
     * 时间戳
     */
    private $timestamp;
    public function __construct(){
        //根据项目常用设置
        $this->appid = "ac3630cc0b5440d893796378b509ec16";
        $this->templateId = "33760";
        $this->dateLimit = 30;
        //不常用设置
        $this->accountSid = '229b19f8788a226a6b4a1198c35085ab';
        $this->token =  '91f4327a762984fc940648d6a30f750d';
        $this->timestamp = date("YmdHis") + 7200;
    }

    /**
     * @return string
     * 包头验证信息,使用Base64编码（账户Id:时间戳）
     */
    private function getAuthorization()
    {
        $data = $this->accountSid . ":" . $this->timestamp;
        return trim(base64_encode($data));
    }

    /**
     * @return string
     * 验证参数,URL后必须带有sig参数，sig= MD5（账户Id + 账户授权令牌 + 时间戳，共32位）(注:转成大写)
     */
    private function getSigParameter()
    {
        $sig = $this->accountSid . $this->token . $this->timestamp;
        return strtoupper(md5($sig));
    }

    /**
     * @param $url
     * @param string $type
     * @return mixed|string
     */
    private function getResult($url, $method, $body = null, $type = 'json')
    {
        $data = $this->connection($url,$body,$type,$method);
        if (isset($data) && !empty($data)) {
            $result = $data;
        } else {
            $result = '没有返回数据';
        }
        return $result;
    }

    /**
     * @param $url
     * @param $type
     * @param $body  post数据
     * @param $method post或get
     * @return mixed|string
     */
    private function connection($url, $body, $type,$method)
    {
        if ($type == 'json') {
            $mine = 'application/json';
        } else {
            $mine = 'application/xml';
        }
        if (function_exists("curl_init")) {
            $header = array(
                'Accept:' . $mine,
                'Content-Type:' . $mine . ';charset=utf-8',
                'Authorization:' . $this->getAuthorization(),
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            if($method == 'post'){
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $opts = array();
            $opts['http'] = array();
            $headers = array(
                "method" => strtoupper($method),
            );
            $headers[]= 'Accept:'.$mine;
            $headers['header'] = array();
            $headers['header'][] = "Authorization: ".$this->getAuthorization();
            $headers['header'][]= 'Content-Type:'.$mine.';charset=utf-8';

            if(!empty($body)) {
                $headers['header'][]= 'Content-Length:'.strlen($body);
                $headers['content']= $body;
            }

            $opts['http'] = $headers;
            $result = file_get_contents($url, false, stream_context_create($opts));
        }
        return $result;
    }
    /**
     * @param $appId
     * @param $to
     * @param $templateId
     * @param null $param
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    private function templateSMS($appId,$to,$templateId,$param=null,$type = 'json'){
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '/Messages/templateSMS?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('templateSMS'=>array(
                'appId'=>$appId,
                'templateId'=>$templateId,
                'to'=>$to,
                'param'=>$param
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <templateSMS>
                            <templateId>'.$templateId.'</templateId>
                            <to>'.$to.'</to>
                            <param>'.$param.'</param>
                            <appId>'.$appId.'</appId>
                        </templateSMS>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, 'post', $body, $type);
        return $data;
    }
    /*
     * 产生随机验证码，传入参数表位数，默认为4
     * */
    public function randToken($num = 4){
        $token = 0;
        for($i = 0; $i < $num-1; $i++){
            $token .= rand(0,9);
        }
        return $token;
    }
    /*
     * 发送验证短信
     * 返回true和false表示成功或失败
     */
    public function sendToken($number, $templateId, $param) {
        $response = $this->templateSMS($this->appid, $number, $templateId, $param);
        $json = json_decode($response, true);
        $result = $json['resp']['respCode'];
        return $result == "000000" ? true : false;
    }
    /*
     * 功能调用接口
     */
    public function Token($number){
        //产生验证码
        $token = $this->randToken();
        //产生参数
        $param = $token.",".$this->dateLimit;

        $expire = time()+$this->dateLimit*60;
        //发送验证码
        if($this->sendToken($number, $this->templateId, $param)){
//        if(1) {
            return ['mobile'=>$number, 'token'=>$token, 'expire'=>$expire];
        }else{
            return false;
        }
    }
}

?>