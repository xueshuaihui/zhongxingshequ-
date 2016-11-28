<?php
require_once MODEL.'mdbModel.php';

class baseRepository {
    protected function table($table){
        return mdbModel::model($table);
    }

    protected function randNum ($size = 6) {
        $result = '';
        $randString = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        for($i = 0; $i < $size; $i++){
            $result .= $randString{rand(0, strlen($randString))};
        }
        return $result;
    }
}