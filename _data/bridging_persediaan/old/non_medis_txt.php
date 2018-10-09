<?php

ini_set('display_errors',1);

require_once 'inc_db.php';

$tglAwal     	= '2017-01-01 00:00:00';
$tglAkhir    	= '2017-12-31 23:59:59';
$kdLokasi   	= '024042200415661003KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115199';
$kdKbrg      	= '1010399999';
$panjangKode 	= '20';

$posts  		= array();

//masuk
$sqlMasuk = "
	SELECT
		'{$kdLokasi}' kd_lokasi,
		'' kd_lokasi2,
		DATE_FORMAT(GM.tgljam_masuk,'%Y') thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(GM.tgljam_masuk,'%Y'),LPAD(DATE_FORMAT(GM.tgljam_masuk,'%m%d'),5,0),'M') nodok,
		NOW() tgldok,
		GM.tgljam_masuk tglbuku,
		CONCAT('{$kdKbrg}',LPAD(GB.ID, '{$panjangKode}', 0)) kd_brg,
		GD.jumlah_masuk kuantitas,
		CONCAT('PEMBELIAN',DATE_FORMAT(GM.tgljam_masuk,'%Y')) keterangan,
		GP.nama asal,
		GM.no_faktur nobukti,
		'M02' jns_trn,
		GD.harga rph_sat,
		'' rph_aset,
		'' flagkirim,
		'' akun,
		LPAD(GB.ID, '{$panjangKode}', 0) AS kd_brg2,
		CONCAT('_',GB.nama) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		GS.satuan
	FROM
		simrs.t_gudang_non_medis_masuk GM
	LEFT JOIN simrs.m_gudang_non_medis_pemasok GP ON GP.ID = GM.m_gudang_non_medis_pemasok_ID
	LEFT JOIN simrs.t_gudang_non_medis_masuk_detail GD ON GD.t_gudang_non_medis_masuk_ID = GM.ID
	LEFT JOIN simrs.m_gudang_non_medis_barang GB ON GB.ID = GD.m_gudang_non_medis_barang_ID
	LEFT JOIN simrs.m_gudang_non_medis_kelompok GK ON GK.ID = GB.kelompok_ID
	LEFT JOIN simrs.m_gudang_non_medis_satuan GS ON GS.ID = GB.satuan_ID
	WHERE
		GM.tgljam_masuk BETWEEN '{$tglAwal}'
	AND '{$tglAkhir}'
	AND (
		GD.hapus = 0
		OR GD.hapus IS NULL
		OR GD.hapus = ''
	)
	AND GB.ID > 0
	AND (GB.aset IS NULL OR GB.aset = 0)
	AND GD.jumlah_masuk > 0
";

$rsMasuk = mysql_query($sqlMasuk,$connection);

while($rowMasuk = mysql_fetch_object($rsMasuk)) {
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
		'{$kdLokasi}' kd_lokasi,
		'' kd_lokasi2,
		DATE_FORMAT(GMK.tgljam_minta, '%Y') thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(GMK.tgljam_minta, '%Y'),LPAD(DATE_FORMAT(GMK.tgljam_minta, '%y%m'),5,0),'K') nodok,
		NOW() tgldok,
		CONCAT(LAST_DAY(DATE_FORMAT(GMK.tgljam_minta,'%Y-%m-%d')),' 23:59:59') tglbuku,
		CONCAT('{$kdKbrg}',LPAD(GB.ID, '{$panjangKode}', 0)) kd_brg,
		SUM(GD.jumlah_minta) kuantitas,
		CONCAT('SUMMARY',' ','PENGELUARAN',' ',DATE_FORMAT(GMK.tgljam_minta, '%Y-%m'),' ',GB.nama) keterangan,
		CONCAT('PENGELUARAN',DATE_FORMAT(GMK.tgljam_minta, '%Y')) nobukti,
		R.nama AS asal,
		'K01' jns_trn,
		'' rph_sat,
		'' rph_aset,
		'' flagkirim,
		'' akun,
		LPAD(GB.ID, '{$panjangKode}', 0) AS kd_brg2,
		CONCAT('_',GB.nama) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		GS.satuan
	FROM
		simrs.t_gudang_non_medis_keluar GMK
	LEFT JOIN intranet_rsup.rsup_ruangan R ON R.ID = GMK.ruangan_ID
	LEFT JOIN simrs.t_gudang_non_medis_keluar_detail GD ON GD.t_gudang_non_medis_keluar_ID = GMK.ID
	LEFT JOIN simrs.m_gudang_non_medis_barang GB ON GB.ID = GD.m_gudang_non_medis_barang_ID
	LEFT JOIN simrs.m_gudang_non_medis_kelompok GK ON GK.ID = GB.kelompok_ID
	LEFT JOIN simrs.m_gudang_non_medis_satuan GS ON GS.ID = GB.satuan_ID
	WHERE
		GMK.tgljam_minta BETWEEN '{$tglAwal}'
	AND '{$tglAkhir}'
	AND (
		GD.hapus = 0
		OR GD.hapus IS NULL
		OR GD.hapus = ''
	)
	AND GB.ID > 0
	AND (GB.aset IS NULL OR GB.aset = 0)
	AND GD.jumlah_minta > 0
	GROUP BY
		DATE_FORMAT(GMK.tgljam_minta, '%Y%m'),
		GB.ID
";

$rsKeluar = mysql_query($sqlKeluar,$connection);

while($rowKeluar = mysql_fetch_object($rsKeluar)) {
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
		number_format(0, 2, '.', ''),
		number_format($rowKeluar->kuantitas * 0, 2, '.', ''),
		$rowKeluar->flagkirim,
	);
}

header('Content-type: text/plain');	
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