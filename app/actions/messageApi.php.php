<?php
require_once 'baseApi.php';
require_once REPOSITORY.'messageRepository';

class messageApi extends baseApi {
    protected $tool;

    public function __construct() {
        parent::__construct();
        $this->tool = new messageRepository();
    }

    public function sendPersonMessage() {
        
    }

    public function getPublicMessage() {
        
    }

    public function getPersonMessage() {
        
    }

    public function ignoreMessage() {
        
    }

    public function getTips() {

    }
}