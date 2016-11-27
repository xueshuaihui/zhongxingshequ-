<?php
require_once 'baseModel.php';
require_once INTER.'dbInterface.php';
use discuz\inter\dbInterface;

class mdbModel extends baseModel implements dbInterface {
    private $table;
    private $field = '';
    private $limit = '';
    private $order = '';
    private $where = '';
    private $sql = '';
    private $prefix = 'zx_';
    private static $models = [];
    public function __construct($table) {
        parent::__construct();
        $this->table = $table;
    }

    public static function model($table) {
        if(!isset(self::$models[$table])){
            self::$models[$table] = new mdbModel($table);
        }
        return self::$models[$table];
    }

    public function store($data, $returnId = 1) {
        return DB::insert($this->table, $data, $returnId);
    }

    public function delete() {

    }

    public function update($data) {
        return DB::update($this->table, $data, $this->where);
    }

    public function find($fields, $sql = false) {
        $this->fields($fields);
        $field = $this->field === '' ? '*' : $this->field;
        $this->sql = 'SELECT '.$field.
            ' FROM '.$this->prefix.$this->table. ' WHERE 1 '.$this->where;
        if($this->order != ''){
            $this->sql .= ' ORDER BY \''.$this->order.'\'';
        }
        if($this->limit != ''){
            $this->sql .=  'LIMIT '.$this->limit;
        }
        if($sql){
            return $this->sql;
        }
        return DB::fetch_first($this->sql);
    }

    public function select($fields, $sql = false) {
        $this->find($fields, true);
        if($sql){
            return $this->sql;
        }
        return DB::fetch_all($this->sql);
    }

    public function fields($fields = []) {
        foreach ($fields as $field){
            $this->field($field);
        }
        return $this;
    }

    public function field($field = '') {
        $this->field .= '`'.$field.'`' . ',';
        return $this;
    }

    public function limit($s, $l) {
        $this->limit = $s.','.$l;
        return $this;
    }

    public function order($order) {
        $this->order = $order;
        return $this;
    }

    public function where($k, $v = null) {
        if(is_string($k) && $v){
            $this->where = 'AND '.$k.' = \''.$v.'\'';
        }elseif(is_array($k)){
            foreach ($k as $key => $value) {
                $this->where .= ' AND '.$key.' = \''.$value.'\'';
            }
        }
        return $this;
    }
}