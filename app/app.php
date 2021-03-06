<?php
namespace discuz;
class app {
    protected static $instance = null;
    protected static $request;
    protected static $response;
    protected static $action;
    protected static $method;
    protected static $path;

    public function __construct($classes = ['request', 'response']) {
        foreach ($classes as $class){
            require_once API.$class.'.php';
        }
        self::$request = request::getRequest();
        $this->init();
        $a = new self::$action;
        $m = self::$method;
        if(self::$path == 'App'){
            define('SHOW', 1);
            return $a->$m();
        }elseif(self::$path == 'Api'){
            $r = self::$response = response::getResponse($a->$m());
            return $r->send();
        }else{
            return false;
        }
    }

    public static function run() {
        if(is_null(self::$instance)){
            self::$instance = new app();
        }
        return self::$instance;
    }

    private function init () {
        ini_set('date.timezone','Asia/Shanghai');
        if(self::$request->get('show')){
            self::$path = 'App';
            $param = self::$request->get('show');
        }else{
            self::$path = 'Api';
            $param = self::$request->get('action');
        }
        if(is_null($param)) {
            exit('error no action');
        }
        $param = explode('-', $param);
        self::$action = $param[0].self::$path;
        self::$method = $param[1];
        require_once API.'actions'.DIRECTORY_SEPARATOR.$param[0].self::$path.'.php';
    }
}