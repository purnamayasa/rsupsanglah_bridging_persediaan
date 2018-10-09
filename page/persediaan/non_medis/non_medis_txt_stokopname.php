<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

$tgl   = isset($_GET['tgl']) ? $_GET['tgl'] : '';

if (empty($tgl)) {
	die;
}

$tgl = str_replace(':','',$tgl);
$tgl = str_replace('-','',$tgl);
$tgl = str_replace(' ','',$tgl);

$kdLokasi   	= '024042200415661003KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115199';
$kdKbrg      	= '1010399999';
$panjangKode 	= '20';

//masuk

$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(T1.tgl_stok, '%Y') AS thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(T1.tgl_stok, '%Y'),LPAD(DATE_FORMAT(T1.tgl_stok, '%m%d'),5,0),'P') AS nodok,
		NOW() AS tgldok,
		T1.tgl_stok AS tglbuku,
		CONCAT('{$kdKbrg}',	LPAD(T4.ID, '{$panjangKode}', 0)) AS kd_brg,
		T1.masuk1 AS kuantitas,
		CONCAT('STOKOPNAMEMASUK',DATE_FORMAT(T1.tgl_stok, '%Y')) AS keterangan,
		NULL AS asal,
		CONCAT('STOKOPNAMEMASUK',DATE_FORMAT(T1.tgl_stok, '%Y')) AS nobukti,
		'P01' AS jns_trn,
		T1.harga AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		'' AS akun,
		LPAD(T4.ID, '{$panjangKode}', 0) AS kd_brg2,
		CONCAT('_', T4.nama) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		T6.satuan
	FROM
		simrs.t_gudang_non_medis_stok_opname AS T1
	LEFT JOIN simrs.m_gudang_non_medis_barang AS T4 ON T4.ID = T1.kode_barang
	LEFT JOIN simrs.m_gudang_non_medis_kelompok AS T5 ON T5.ID = T4.kelompok_ID
	LEFT JOIN simrs.m_gudang_non_medis_satuan AS T6 ON T6.ID = T4.satuan_ID
	WHERE
		T1.tgl_stok BETWEEN '{$tgl}'
	AND '{$tgl}'
	AND T1.masuk1 > '0'
	AND T1.posting = '1'
	AND T4.ID > '0'
	AND T5.kelompok <> 'INVENTARIS'
";

$rsMasuk = $mysql_2_13->query($sqlMasuk);

while($rowMasuk = $rsMasuk->fetch_object()) {
	$posts[] = array(
		$rowMasuk->kd_lokasi,
		$rowMasuk->ur_brg2,
		$rowMasuk->thn_ang,
		$rowMasuk->nodok,
		date('d-m-Y H:i:s',strtotime($rowMasuk->tgldok)),
		date('d-m-Y H:i:s',strtotime($rowMasuk->tglbuku)),
		$rowMasuk->kd_kbrg2,
		$rowMasuk->kd_brg2,
		number_format($rowMasuk->kuantitas, 2, '.', ''),
		$rowMasuk->satuan,
		$rowMasuk->asal,
		$rowMasuk->nobukti,
		$rowMasuk->jns_trn,
		number_format($rowMasuk->rph_sat, 2, '.', ''),
		number_format($rowMasuk->kuantitas * $rowMasuk->rph_sat, 2, '.', ''),
		$rowMasuk->flagkirim,
	);
}

//keluar
$sqlKeluar = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(T1.tgl_stok, '%Y') AS thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(T1.tgl_stok, '%Y'),LPAD(DATE_FORMAT(T1.tgl_stok, '%m%d'),5,0),'P') AS nodok,
		NOW() AS tgldok,
		T1.tgl_stok AS tglbuku,
		CONCAT('{$kdKbrg}',	LPAD(T4.ID, '{$panjangKode}', 0)) AS kd_brg,
		T1.keluar1 AS kuantitas,
		CONCAT('STOKOPNAMEKELUAR',DATE_FORMAT(T1.tgl_stok, '%Y')) AS keterangan,
		NULL AS asal,
		CONCAT('STOKOPNAMEKELUAR',DATE_FORMAT(T1.tgl_stok, '%Y')) AS nobukti,
		'P02' AS jns_trn,
		T1.harga AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		'' AS akun,
		LPAD(T4.ID, '{$panjangKode}', 0) AS kd_brg2,
		CONCAT('_', T4.nama) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		T6.satuan
	FROM
		simrs.t_gudang_non_medis_stok_opname AS T1
	LEFT JOIN simrs.m_gudang_non_medis_barang AS T4 ON T4.ID = T1.kode_barang
	LEFT JOIN simrs.m_gudang_non_medis_kelompok AS T5 ON T5.ID = T4.kelompok_ID
	LEFT JOIN simrs.m_gudang_non_medis_satuan AS T6 ON T6.ID = T4.satuan_ID
	WHERE
		T1.tgl_stok BETWEEN '{$tgl}'
	AND '{$tgl}'
	AND T1.keluar1 > '0'
	AND T1.posting = '1'
	AND T4.ID > '0'
	AND T5.kelompok <> 'INVENTARIS'
";

$rsKeluar = $mysql_2_13->query($sqlKeluar);

while($rowKeluar = $rsKeluar->fetch_object()) {
	$posts[] = array(
		$rowKeluar->kd_lokasi,
		$rowKeluar->ur_brg2,
		$rowKeluar->thn_ang,
		$rowKeluar->nodok,
		date('d-m-Y H:i:s',strtotime($rowKeluar->tgldok)),
		date('d-m-Y H:i:s',strtotime($rowKeluar->tglbuku)),
		$rowKeluar->kd_kbrg2,
		$rowKeluar->kd_brg2,
		number_format($rowKeluar->kuantitas, 2, '.', ''),
		$rowKeluar->satuan,
		$rowKeluar->asal,
		$rowKeluar->nobukti,
		$rowKeluar->jns_trn,
		number_format($rowKeluar->rph_sat, 2, '.', ''),
		number_format($rowKeluar->kuantitas * $rowKeluar->rph_sat, 2, '.', ''),
		$rowKeluar->flagkirim,
	);
}

header('Content-type: text/plain');
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=non_medis_stokopname_{$tgl}.txt");		

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