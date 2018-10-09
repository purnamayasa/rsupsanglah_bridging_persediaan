<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

include 'menu.php';

if (isset($url_params[0]) && $url_params[0] == 'download_cara_bayar_simrs') {
	include 'download_cara_bayar_simrs.php';
} else if (isset($url_params[0]) && $url_params[0] == 'download_pasien_simrs') {
	include 'download_pasien_simrs.php';
} else if (isset($url_params[0]) && $url_params[0] == 'download_pasien_kunjungan_simrs') {
	include 'download_pasien_kunjungan_simrs.php';
} else if (isset($url_params[0]) && $url_params[0] == 'download_pasien_cara_bayar_simrs') {
	include 'download_pasien_cara_bayar_simrs.php';
} 