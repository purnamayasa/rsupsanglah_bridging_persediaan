<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

$tglAwal    = isset($_GET['tglawal']) ? $_GET['tglawal'] : '';
$tglAkhir   = isset($_GET['tglakhir']) ? $_GET['tglakhir'] : '';

if (empty($tglAwal)) {
	die;
}

if (empty($tglAkhir)) {
	die;
}