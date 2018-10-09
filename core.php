<?php

define('ROOT', __DIR__);
define('FOLDER_CLASS', ROOT.'/'.'class');
define('FOLDER_FUNCTION', ROOT.'/'.'function');
define('FOLDER_INCLUDE', ROOT.'/'.'include');
define('FOLDER_PAGE', ROOT.'/'.'page');
define('FOLDER_TEMPLATE', ROOT.'/'.'template');
define('FOLDER_VENDOR', ROOT.'/'.'vendor');
define('URL','http://localhost/rsupsanglah/');

$ajax = isset($_GET['ajax']) ? $_GET['ajax'] : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = explode('/', $url);
$page = isset($url[0]) ? $url[0] : '';
unset($url[0]);

$url_params = array_values($url);