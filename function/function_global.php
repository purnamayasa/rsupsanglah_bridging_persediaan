<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

function show_log($log) {
	echo '<pre>';
	print_r($log);
	echo '</pre>';
}

function month_name($month, $type) {
	if ($month == '1' || $month == '01') {
		if ($type == 0) {
			return 'Januari';
		} else if ($type == 1) {
			return 'Januari';
		}
	} else if ($month == '2' || $month == '02') {
		if ($type == 0) {
			return 'Februari';
		} else if ($type == 1) {
			return 'Februari';
		}
	} else if ($month == '3' || $month == '03') {
		if ($type == 0) {
			return 'Maret';
		} else if ($type == 1) {
			return 'November';
		}
	} else if ($month == '4' || $month == '04') {
		if ($type == 0) {
			return 'April';
		} else if ($type == 1) {
			return 'April';
		}
	} else if ($month == '5' || $month == '05') {
		if ($type == 0) {
			return 'Mei';
		} else if ($type == 1) {
			return 'Mei';
		}
	} else if ($month == '6' || $month == '06') {
		if ($type == 0) {
			return 'Juni';
		} else if ($type == 1) {
			return 'Juni';
		}
	} else if ($month == '7' || $month == '07') {
		if ($type == 0) {
			return 'Juli';
		} else if ($type == 1) {
			return 'Juli';
		}
	} else if ($month == '8' || $month == '08') {
		if ($type == 0) {
			return 'Agustus';
		} else if ($type == 1) {
			return 'Agustus';
		}
	} else if ($month == '9' || $month == '09') {
		if ($type == 0) {
			return 'September';
		} else if ($type == 1) {
			return 'September';
		}
	} else if ($month == '10' || $month == '10') {
		if ($type == 0) {
			return 'Oktober';
		} else if ($type == 1) {
			return 'Oktober';
		}
	} else if ($month == '11' || $month == '11') {
		if ($type == 0) {
			return 'Nopember';
		} else if ($type == 1) {
			return 'November';
		}
	} else if ($month == '12' || $month == '12') {
		if ($type == 0) {
			return 'Desember';
		} else if ($type == 1) {
			return 'Desember';
		}
	}
}

function copy_database($from_mysql, $from_database, $to_mysql, $to_database) {
	$from_mysql->select_db($from_database);
	$to_mysql->query("DROP DATABASE IF EXISTS `$to_database`");
	$to_mysql->query("CREATE DATABASE `$to_database`");
    $to_mysql->select_db ($to_database);

    $getTables =  $from_mysql->query("SHOW TABLES");   
    while($row_table = $getTables->fetch_row()) {
        $rsTable = $from_mysql->query("SHOW CREATE TABLE `{$row_table[0]}`");
        $tableInfo = $rsTable->fetch_array();
        $to_mysql->query("DROP TABLE IF EXISTS `{$row_table[0]}`");
        $to_mysql->query($tableInfo[1]);
        $rs = $from_mysql->query("SELECT * FROM `{$row_table[0]}`");
        while ($row = $rs->fetch_array(MYSQL_ASSOC)) {	
        	show_log("INSERT INTO `{$row_table[0]}` (`".implode("`, `",array_keys($row))."`) VALUES ('".implode("', '",array_values($row))."')");
        	$to_mysql->query("INSERT INTO `{$row_table[0]}` (`".implode("`, `",array_keys($row))."`) VALUES ('".implode("', '",array_values($row))."')");
        }
    }
}