<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

class class_column
{
    private $database;
    private $table;
    private $columns;
    
    public function __construct($database, $table)
    {
        $this->database = $database;
        $this->table = $table;
        $this->columns[$this->database][$this->table] = array();
    }
    
    public function set($column, $value = null) {
        $this->columns[$this->database][$this->table][$column] = array(
            'column' => $column,
            'value' => $value
        );
    }
    
    public function get($column)
    {
        if (isset($this->columns[$this->database][$this->table][$column])) {
            return $this->columns[$this->database][$this->table][$column]['value'];
        } else {
            return null;
        }
    }
    
    public function get_columns()
    {
        return $this->columns;
    }

    public function clear_columns() {
        $this->columns[$this->database][$this->table] = array();
    }
}