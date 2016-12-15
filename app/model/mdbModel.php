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
    private static $baseModel;
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

    public static function baseModel() {
        if(!isset(self::$baseModel)){
            self::$baseModel = new baseModel();
        }
        return self::$baseModel;
    }

    public function store($data, $returnId = true, $replace = false) {
        $do = DB::insert($this->table, $data, $returnId, $replace);
        self::refresh();
        return $do;
    }

    public function delete() {
        $do = DB::delete($this->table, '1 '.$this->where.$this->whereOr);
        self::refresh();
        return $do;
    }

    public function update($data) {
        $do = DB::update($this->table, $data, '1 '.$this->where.$this->whereOr);
        self::refresh();
        return $do;
    }

    public function increase($data, $data2 = [], $sql =false) {
        $temp = '';
        foreach ($data as $k=>$value){
            if(is_array($value)){
                $temp .= '`'.$k.'` = `'.$k.'` '.$value[0].' '.$value[1].',';
            }else{
                $temp .= '`'.$k.'` = `'.$k.'` + '.$value.',';
            }
        }
        foreach ($data2 as $k=>$value){
            $temp .= '`'.$k.'` = '.$value.',';
        }
        $this->sql = 'UPDATE '.$this->prefix.$this->table.' SET '.trim($temp, ',').' WHERE 1 '.$this->where.$this->whereOr;
        if($sql){
            return $this->sql;
        }
        $do = DB::query($this->sql);
        self::refresh();
        return $do;
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
            $this->sql .= ' ORDER BY '.$this->order;
        }
        if($this->limit){
            $this->sql .=  ' LIMIT '.trim($this->limit,',');
        }
        if($sql){
            $do = $this->sql;
            self::refresh();
            return $do;
        }
        $do = DB::fetch_first($this->sql);
        self::refresh();
        return $do;
    }

    public function select($fields, $sql = false) {
        $this->sql = $this->find($fields, true);
        if($sql){
            $do = $this->sql;
            self::refresh();
            return $do;
        }
        $do = DB::fetch_all($this->sql);
        self::refresh();
        return $do;
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
        $this->field .= ' '.trim($field).' ,';
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
        $this->where .= ' AND '.$k.' IN ('.trim($range, ',').')';
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

    public function whereWhere($k, $c = '=', $v = null) {
        if(is_string($k)){
            $this->where .= ' AND '.$k.' '.$c.' \''.$v.'\'';
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

    private function refresh() {
        unset($this->field);
        unset($this->limit);
        unset($this->order);
        unset($this->where);
        unset($this->whereOr);
        unset($this->sql);
        unset($this->join);
    }
}