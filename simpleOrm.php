<?php

abstract class ActiveRecord{
    protected static $table;
    protected $fieldValues;
    public $select;

    static function findById($id){
        $query = "select * from ".static::$table."where id = $id";
        return self::createDomain($query);
    }

    function get($fieldname){
        return $this->fieldValues[$fieldname];
    }

    static function __callStatic($method, $args){
        $field = preg_replace('/^findBy(\w*)$/','${1}',$method);
        $query = "select * from ".static::$table."where $field='$args[0]'";
        return self::createDomain($query);
    }
    
    private static function createDomain($query){
        $className = get_called_class();
        $domain = new $className();
        $domain->fieldValues = array();
        $domain->select = $query;
        
        foreach($className::$fields as $field => $type){
            $domain->fieldValues[$field] = 'TODO::set from sql result';
        }
        return $domain;
    }
}

class Customer extends ActiveRecord{
    protected static $table ='custdb';
    protected static $fields = array(
        'id' => 'int',
        'email' => 'varchar',
        'lastname' => 'varchar',
    );
}

class Sales extends ActiveRecord{
    protected static $table = 'saledb';
    protected static $fields = array(
        'id' => 'int',
        'item' => 'varchar',
        'qty' => 'int',
    );
}

assert("select * from custdb where id=123" == Customer::findById(123)->select);
assert("TODO::set from sql result" == Customer::findById(123)->email);
assert("select * from salestdb where id=321" == Sales::findById(321)->select);
assert("select * from custdb where Lastname='Denoncourt'" == Customer::findByLastname('Denoncourt')->select);
