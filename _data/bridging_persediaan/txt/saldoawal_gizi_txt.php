<?php

ini_set('display_errors',1);

require_once 'inc_db.php';

$kdLokasi   	= '024042200415661001KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115111';
$kdKbrg      	= '1010799999';
$panjangKode 	= '20';
$thnAng			= '2016';
$tglbuku		= '2016-12-31 23:59:59';

$posts  		= array();

//awal
$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		'{$thnAng}' AS thn_ang,
		CONCAT('{$kdLokasi}','{$thnAng}',LPAD('1231', 5, 0),'M') AS nodok,
		'{$tglbuku}' AS tgldok,
		'{$tglbuku}' AS tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(
				(
					CASE
					WHEN S.kelompok = 'pegawai' THEN
						CONCAT(1, S.kode_barang)	
					WHEN S.kelompok = 'pasien' THEN
						CONCAT(2, S.kode_barang)	
					END
				),
				'{$panjangKode}',
				'0'
			)
		) AS kd_brg,
		S.jumlah_akhir AS kuantitas,
		O.satuan AS keterangan,
		'GUDANGGIZI' AS asal,
		CONCAT('SALDOAWAL','{$thnAng}') AS nobukti,
		'M01' AS jns_trn,
		S.harga AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		'' akun,
		LPAD(
			(
				CASE
				WHEN S.kelompok = 'pegawai' THEN
					CONCAT(1, S.kode_barang)	
				WHEN S.kelompok = 'pasien' THEN
					CONCAT(2, S.kode_barang)	
				END
			),
			'{$panjangKode}',
			'0'
		) AS kd_brg2,
		(
			CASE
			WHEN S.kelompok = 'pegawai' THEN
				CONCAT('_',O.nama_barang,' (Pegawai)')	
			WHEN S.kelompok = 'pasien' THEN
				CONCAT('_',O.nama_barang,' (Pasien)')
			END
		) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		O.satuan
	FROM
		m_gudang_gizi_stokakhirbulan AS S
	INNER JOIN m_gudang_gizi_barang AS O ON O.kode_barang = S.kode_barang
	WHERE
		S.`bulan` = '12'
	AND S.`tahun` = '2016'
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

header('Content-type: text/plain');
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=saldoawal_gizi_{$thnAng}.txt");	
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