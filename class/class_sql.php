<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

class class_sql
{
    private $class_column;
    private $database;
    private $table;
    private $sql;
    private $where;
    
    public function __construct($database, $table)
    {
        $this->database = $database;
        $this->table = $table;
        $this->class_column = new class_column($this->database, $this->table);
    }
    
    public function class_column($class_column = null)
    {
        if (!is_null($class_column)) {
            $this->class_column = $class_column;
        }

        return $this->class_column;
    }
    
    public function where($column, $value)
    {
        $this->where = " WHERE `$this->database`.`$this->table`.`$column` = '$value'";
    }
    
    public function add_where($column, $value, $logical = 'AND')
    {
        $this->where.= " $logical `$this->database`.`$this->table`.`$column` = '$value'";
    }
    
    public function add_where_join($database, $table, $column, $value, $logical = 'AND')
    {
        $this->where.= " $logical `$database`.`$table`.`$column` = '$value'";
    }

    public function clear_where() 
    {
        $this->where = "";
    }
    
    public function select()
    {
        $columns = $this->class_column->get_columns();
        
        $columns = implode(", `$this->database`.`$this->table`.", array_map(function ($column) {
            return '`'.$column['column'].'`';
        }, $columns[$this->database][$this->table]));
        
        $strcolumns = "`$this->database`.`$this->table`.".$columns;
        
        $this->sql = "SELECT $strcolumns FROM `$this->database`.`$this->table` $this->where";
        
        return $this->sql;
    }
    
    public function insert()
    {
        $columns = $this->class_column->get_columns();

        $strcolumns = implode(', ', array_map(function ($column) {
            return '`'.$column['column'].'`';
        }, $columns[$this->database][$this->table]));
        
        $strvalues = implode(', ', array_map(function ($column) {
            return "'".$column['value']."'";
        }, $columns[$this->database][$this->table]));
        
        $this->sql = "INSERT INTO `$this->database`.`$this->table` ($strcolumns) VALUES ($strvalues)";
        
        return $this->sql;
    }
    
    public function update()
    {
        $columns = $this->class_column->get_columns();
        
        $strdata = implode(', ', array_map(function ($column) {
            return '`'.$column['column'].'`'.'='."'".$column['value']."'";
        }, $columns[$this->database][$this->table]));
        
        $this->sql = "UPDATE `$this->database`.`$this->table` SET $strdata $this->where";
        
        return $this->sql;
    }

    public function delete()
    {   
        $this->sql = "DELETE FROM `$this->database`.`$this->table` $this->where";
        
        return $this->sql;
    }
}