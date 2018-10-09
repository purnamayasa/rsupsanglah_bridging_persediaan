<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

class class_connection
{
	private $mysql;
	private $hostname;
	private $username;
	private $password;
	private $database;

    public function __construct()
    {
        
    }

    public function set_hostname($hostname)
    {
    	$this->hostname = $hostname;
    }

    public function set_username($username)
    {
    	$this->username = $username;
    }

    public function set_password($password)
    {
    	$this->password = $password;
    }

    public function set_database($database)
    {
    	$this->database = $database;
    }

    public function open()
    {
    	$this->mysql = new mysqli($this->hostname, $this->username, $this->password, $this->database);

        if ($this->mysql->connect_error) {
		    return false;
		} else {
			return true;
		}
    }

    public function close()
    {
        $this->mysql->close();
    }

    public function mysql()
    {
        return $this->mysql;
    }
}