<?php
require_once 'baseModel.php';
require_once INTER.'dbInterface.php';
use discuz\inter\dbInterface;

class mdbModel extends baseModel implements dbInterface {
    private $table;
    private $field;
    private $limit;
    private $order;
    private $where;
    private $whereOr;
    private $sql;
    private $join;
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
        $field = $this->field == '' ? '*' : trim($this->field, ',');
        $this->sql = 'SELECT '.$field.
            ' FROM '.$this->prefix.$this->table;
        if($this->join){
            $this->sql .= $this->join;
        }
        if($this->where){
            $this->sql .= ' WHERE 1 '.$this->where;
        }
        if($this->whereOr){
            $this->sql .= $this->whereOr;
        }
        if($this->order){
            $this->sql .= ' ORDER BY \''.$this->order.'\'';
        }
        if($this->limit){
            $this->sql .=  ' LIMIT '.$this->limit;
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

    public function fields($fields) {
        if(is_string($fields)){
            $fields = explode(',', $fields);
        }
        foreach ($fields as $k=>$field){
            $this->field($field);
        }
        return $this;
    }

    public function field($field = '') {
        $this->field .= '`'.trim($field).'`' . ',';
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

    public function in ($k, $v) {
        $range = '';
        foreach ($v as $item){
            $range .= '\''.$item.'\',';
        }
        $this->where = ' AND '.$k.' IN ('.trim($range, ',').')';
        return $this;
    }

    public function whereOr ($k, $v = null) {
        if(is_string($k)){
            $this->whereOr = ' OR '.$k.' = \''.$v.'\'';
        }elseif(is_array($k)){
            foreach ($k as $key=>$value){
                $this->whereOr .= ' OR '.$k.' = \''.$v.'\'';
            }
        }
        return $this;
    }

    public function ass ($name) {
        $this->table .= ' AS '.$name;
        return $this;
    }

    public function join($sql) {
        $this->join .= $sql;
        return $this;
    }
}