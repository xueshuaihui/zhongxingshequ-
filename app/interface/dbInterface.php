<?php
namespace discuz\inter;

interface dbInterface
{
    public function store($data, $sql = false);

    public function delete ();

    public function update($data);

    public function find($fields);

    public function fields($fields);

    public function field($field = '');

    public function select($fields);

    public function limit($s, $l);

    public function order($order);

    public function where($k, $v = null);

    public function whereOr($k, $v = null);
}