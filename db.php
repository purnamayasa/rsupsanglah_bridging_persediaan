<?php

ini_set('display_errors', 1);

$hostname = 'localhost';
$port     = '3306';
$username = 'root';
$password = '';
$database = 'information_schema';

$mysql = new mysqli($hostname, $username, $password, $database, $port);

//mysql 10.20.2.4 local persediaan
$hostname = '10.20.2.4';
$port     = '3310';
$username = 'root';
$password = 'root';
$database = 'information_schema';

//$hostname = '36.67.40.162';
//$port     = '3310';
//$username = 'root';
//$password = 'root';
//$database = 'information_schema';

$mysql_2_4_local_persediaan = new mysqli($hostname, $username, $password, $database, $port);

//mysql 10.20.2.13 local simrs
$hostname = '10.20.2.13';
$port     = '3306';
$username = 'root';
$password = 'root';
$database = 'information_schema';

//$hostname = '36.67.40.162';
//$port     = '9133';
//$username = 'root';
//$password = 'root';
//$database = 'information_schema';

$mysql_2_13 = new mysqli($hostname, $username, $password, $database, $port);

//mysql 10.20.2.13 online
//$hostname = '36.67.40.162';
//$port     = '9133';
//$username = 'root';
//$password = 'root';
//$database = 'information_schema';

//$mysql_2_13 = new mysqli($hostname, $username, $password, $database, $port);

//mysql 10.20.2.4 online persediaan
//$hostname = '36.67.40.162';
//$port     = '3310';
//$username = 'root';
//$password = 'root';
//$database = 'information_schema';

//$mysql_2_24_p = new mysqli($hostname, $username, $password, $database, $port);
