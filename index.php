<?php

ini_set('display_errors', 1);
date_default_timezone_set('Asia/Makassar');

session_start();

require_once 'core.php';
require_once 'db.php';
require_once FOLDER_CLASS.'/'.'class_connection.php';
require_once FOLDER_CLASS.'/'.'class_table.php';
require_once FOLDER_CLASS.'/'.'class_column.php';
require_once FOLDER_CLASS.'/'.'class_sql.php';
require_once FOLDER_FUNCTION.'/'.'function_global.php';
require_once FOLDER_FUNCTION.'/'.'function_seo.php';
require_once FOLDER_VENDOR.'/'.'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';

$ctable = new class_table();

if (file_exists(FOLDER_TEMPLATE.'/'.'page'.'.php')) {
	include_once FOLDER_TEMPLATE.'/'.'page'.'.php';
}