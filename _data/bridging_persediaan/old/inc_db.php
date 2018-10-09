<?php
$dbserver  		= "10.20.2.13";
$dbusername		= "root";
$dbpassword		= "root";
$dbname			= "simrs";
$connection = mysql_connect("$dbserver", "$dbusername", "$dbpassword");
$db = mysql_select_db("$dbname");

$dbserver  		= "10.20.2.4:3310";
$dbusername		= "root";
$dbpassword		= "root";
$dbname			= "dbsedia10";
$connection4 = mysql_connect("$dbserver", "$dbusername", "$dbpassword");
$db4 = mysql_select_db("$dbname");
?>