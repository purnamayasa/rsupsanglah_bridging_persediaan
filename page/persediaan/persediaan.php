<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

include 'menu.php';

if (isset($url_params[0]) && $url_params[0] == 'blu') {
	if (isset($url_params[1]) && $url_params[1] == 'copy_db') {
		include 'blu/copy_db.php';
	} else if (isset($url_params[1]) && $url_params[1] == 'create_txt') {
		include 'blu/create_txt.php';
	}
} else if (isset($url_params[0]) && $url_params[0] == 'gizi') {
	if (isset($url_params[1]) && $url_params[1] == 'txt') {
		include 'gizi/gizi_txt.php';
	}
} else if (isset($url_params[0]) && $url_params[0] == 'non_medis') {
	if (isset($url_params[1]) && $url_params[1] == 'txt') {
		include 'non_medis/non_medis_txt.php';
	} else if (isset($url_params[1]) && $url_params[1] == 'txt_stokopname') {
		include 'non_medis/non_medis_txt_stokopname.php';	
    } else if (isset($url_params[1]) && $url_params[1] == 'stokopname') {
		include 'non_medis/non_medis_stokopname.php';
    } else if (isset($url_params[1]) && $url_params[1] == 'dbsedia_blu') {
		include 'non_medis/non_medis_penerimaan_dbsedia_blu.php';	
    }
} else if (isset($url_params[0]) && $url_params[0] == 'farmasi') {
	if (isset($url_params[1]) && $url_params[1] == 'penerimaan_faktur') {
		include 'farmasi/farmasi_penerimaan_faktur.php';
	} else if (isset($url_params[1]) && $url_params[1] == 'penerimaan_tt') {
		include 'farmasi/farmasi_penerimaan_tt.php';
	} else if (isset($url_params[1]) && $url_params[1] == 'stokopname') {
		include 'farmasi/farmasi_stokopname.php';
	} else if (isset($url_params[1]) && $url_params[1] == 'dbsedia_blu') {
		include 'farmasi/farmasi_penerimaan_dbsedia_blu.php';
	} else if (isset($url_params[1]) && $url_params[1] == 'txt') {
		include 'farmasi/farmasi_txt.php';
	}
} else {
	
}
