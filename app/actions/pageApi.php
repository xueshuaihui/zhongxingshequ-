<?php
require_once 'baseApi.php';
require_once REPOSITORY.'pageRepository';

class pageApi extends baseApi {
    protected $tool;
    public function __construct() {
        parent::__construct();
        $this->tool = new pageRepository();
    }

    public function postPage() {

    }

    public function threadView() {
        
    }

    public function threadList() {
        
    }
}