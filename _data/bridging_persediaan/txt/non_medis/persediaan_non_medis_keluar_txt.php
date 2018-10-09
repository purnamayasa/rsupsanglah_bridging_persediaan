<?php

ini_set('display_errors',1);

require_once 'inc_db.php';


$tglAwal    = isset($_GET['tglawal']) ? $_GET['tglawal'] : '';
$tglAkhir   = isset($_GET['tglakhir']) ? $_GET['tglakhir'] : '';

if (empty($tglAwal)) {
	die;
}

if (empty($tglAkhir)) {
	die;
}

$posts 		= array();

require_once 'non_medis_mkeluar_txt.php';

$tglAwalx = str_replace(':','_',$tglAwal);
$tglAwalx = str_replace('-','_',$tglAwalx);
$tglAwalx = str_replace(' ','_',$tglAwalx);
$tglAkhirx = str_replace(':','_',$tglAkhir);
$tglAkhirx = str_replace('-','_',$tglAkhirx);
$tglAkhirx = str_replace(' ','_',$tglAkhirx);

header('Content-type: text/plain');
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=non_medis_keluar_{$tglAwalx}__{$tglAkhirx}.txt");	

foreach($posts as $index => $post) {		
	if(is_array($post)) {
		foreach($post as $key => $value) {
			if ($key == 0) { //1
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 1) { //2
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 2) { //3
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 3) { //4
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 4) { //5
				$post[$key] = $value;
			} else if ($key == 5) { //6
				$post[$key] = $value;
			} else if ($key == 6) { //7
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 7) { //8
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 8) { //9
				$post[$key] = $value;
			} else if ($key == 9) { //10
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 10) { //11
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 11) { //12
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 12) { //13
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 13) { //14
				$post[$key] = $value;
			} else if ($key == 14) { //15
				$post[$key] = $value;
			} else if ($key == 15) { //16
				$post[$key] = '|'.trim($value).'|';
			} else {
				$post[$key] = $value;
			}
		}
		
		echo implode(',', $post);
	}
	echo "\n";
}