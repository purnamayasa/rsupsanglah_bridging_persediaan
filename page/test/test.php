<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

$ctable->class_connection()->set_hostname('localhost');
$ctable->class_connection()->set_username('purnamay_db');
$ctable->class_connection()->set_password('purnama124185');
$ctable->class_connection()->set_database('purnamay_db');
$ctable->class_connection()->open();
$ctable->set_database('sik');
$ctable->set_table('bangsal');
$ctable->open();

//all data
$ctable->class_column()->set('kd_bangsal');
$ctable->class_column()->set('nm_bangsal');
$rs = $ctable->crud(URL.'index.php?url=test');
echo '<hr>';

die;

//insert
$ctable->class_column()->set('namabank', 'SINAR');
$ctable->insert();

//all data
$ctable->class_column()->set('namabank');
$rs = $ctable->select();
if ($rs) {
	while ($row = $rs->fetch_object()) {
		show_log($row);
	}
}
echo '<hr>';

//update
$ctable->class_column()->set('namabank', 'SINAR 1');
$ctable->class_sql()->where('namabank', 'SINAR');
$ctable->update();
$ctable->class_sql()->clear_where();

//all data
$ctable->class_column()->set('namabank');
$rs = $ctable->select();
if ($rs) {
	while ($row = $rs->fetch_object()) {
		show_log($row);
	}
}
echo '<hr>';

//delete
$ctable->class_sql()->where('namabank', 'SINAR 1');
$ctable->delete();
$ctable->class_sql()->clear_where();

//all data
$ctable->class_column()->set('namabank');
$rs = $ctable->select();
if ($rs) {
	while ($row = $rs->fetch_object()) {
		show_log($row);
	}
}
echo '<hr>';

$ctable->class_connection()->close();