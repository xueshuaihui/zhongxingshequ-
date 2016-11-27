<?php
namespace discuz\inter;

interface requestInterface
{
    public static function getRequest();

    public function get();

    public function post();
}