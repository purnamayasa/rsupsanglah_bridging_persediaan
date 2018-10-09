<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

class class_table
{
    private $class_connection;
    private $class_sql;
    private $class_columns;
    private $database;
    private $table;
    private $pk;
    
    public function __construct()
    {
    	$this->class_connection = new class_connection();
    }

    public function class_connection($connection = null)
    {
    	if (!is_null($connection)) {
        	$this->class_connection = $connection;
    	}

    	return $this->class_connection;
    }

    public function class_column($class_column = null)
    {
        if (!is_null($class_column)) {
            $this->class_column = $class_column;
        }

        return $this->class_columns;
    }

    public function class_sql($class_sql = null)
    {
        if (!is_null($class_sql)) {
            $this->class_sql = $class_sql;
        }

        return $this->class_sql;
    }

    public function set_database($database)
    {
        $this->database = $database;
    }

    public function set_table($table)
    {
        $this->table = $table;
    }

    public function open()
    {
        $this->class_sql = new class_sql($this->database, $this->table);
        $this->class_columns = new class_column($this->database, $this->table);
    }

    public function select()
    {
        $this->class_sql->class_column($this->class_columns);
        $sql = $this->class_sql->select();
        $qry = $this->class_connection->mysql()->query($sql);
        
        return $qry;
    }

    public function insert() 
    {
        $this->class_sql->class_column($this->class_columns);
        $sql = $this->class_sql->insert();
        $qry = $this->class_connection->mysql()->query($sql);

        return $qry;
    }

    public function update() 
    {
        $this->class_sql->class_column($this->class_columns);
        $sql = $this->class_sql->update();
        $qry = $this->class_connection->mysql()->query($sql);

        return $qry;
    }

    public function delete() 
    {   
        $this->class_sql->class_column($this->class_columns);
        $sql = $this->class_sql->delete();
        $qry = $this->class_connection->mysql()->query($sql);

        return $qry;
    }

    public function crud($url, $action = 'list')
    {
        if ($action == 'list') {
            $this->class_sql->class_column($this->class_columns);
            $sql = $this->class_sql->select();
            $qry = $this->class_connection->mysql()->query($sql);
            if ($qry) {
                echo $this->table;
                echo '<table width="100%" border="1">';
                echo '<tr>';
                echo '<th>No</th>';
                foreach ($this->class_columns->get_columns() as $key1 => $value1) {
                    foreach ($value1 as $key2 => $value2) {
                        foreach ($value2 as $key3 => $value3) {
                            echo '<th align="left">'.$value3['column'].'</th>';
                        }
                    }
                }
                echo '<th>Action</th>';
                echo '</tr>';
                while ($row = $qry->fetch_object()) {
                    echo '<tr>';
                    echo '<td></td>';
                    foreach ($this->class_columns->get_columns() as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            foreach ($value2 as $key3 => $value3) {
                                echo '<td>'.$row->{$value3['column']}.'</td>';
                            }
                        }
                    }
                    $edit = '<a href="'.$url.'/'.'edit'.'">Edit</a>';
                    $delete = '<a href="'.$url.'/'.'delete">Delete</a>';
                    echo "<td>{$edit} {$delete}</td>";
                    echo '</tr>';
                }
                echo '</table>';
            }
        }
    }
}