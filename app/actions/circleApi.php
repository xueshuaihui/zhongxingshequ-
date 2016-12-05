<?php
require_once 'baseApi.php';
require_once REPOSITORY.'circleRepository';

class circleApi extends baseApi {
    protected $tool;

    public function __construct() {
        parent::__construct();
        $this->tool = new circleRepository();
    }

    public function circleList() {
        
    }

    public function circleSearch() {

    }

    public function inviteFriend() {
        
    }

    public function agreeInvite() {

    }

    public function applyJoinCircle() {
        
    }

    public function circleMove() {
        
    }

    public function quitCircle() {
        
    }

    public function createCircle() {
        
    }

    public function ignoreMessage() {
        
    }

    public function getManagePower() {

    }
}