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

$tglAwalx = str_replace(':','',$tglAwal);
$tglAwalx = str_replace('-','',$tglAwalx);
$tglAwalx = str_replace(' ','',$tglAwalx);
$tglAkhirx = str_replace(':','',$tglAkhir);
$tglAkhirx = str_replace('-','',$tglAkhirx);
$tglAkhirx = str_replace(' ','',$tglAkhirx);

$kdLokasi   	= '024042200415661001KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115111';
$kdKbrg      	= '1010799999';
$panjangKode 	= '20';

//masuk
$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(GZM.tgl,'%Y') AS thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(GZM.tgl,'%Y'),LPAD(DATE_FORMAT(GZM.tgl, '%m%d'),5,'0'),'M') AS nodok,
		NOW() AS tgldok,
		GZM.tgl AS tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(
				(
					CASE
					WHEN GZM.kelompok = 'pegawai' THEN
						CONCAT(1, GZ.kode_barang)	
					WHEN GZM.kelompok = 'pasien' THEN
						CONCAT(2, GZ.kode_barang)	
					END
				),
				'{$panjangKode}',
				'0'
			)
		) AS kd_brg,
		GZ.jumlah AS kuantitas,
		CONCAT('PEMBELIAN',DATE_FORMAT(GZM.tgl,'%Y')) AS keterangan,
		SP.nama_sup AS asal,
		GZM.no_faktur AS nobukti,
		'M02' AS jns_trn,
		GZ.harga AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		'' akun,
		LPAD(
			(
				CASE
				WHEN GZM.kelompok = 'pegawai' THEN
					CONCAT(1, GZ.kode_barang)	
				WHEN GZM.kelompok = 'pasien' THEN
					CONCAT(2, GZ.kode_barang)	
				END
			),
			'{$panjangKode}',
			'0'
		) AS kd_brg2,
		(
			CASE
			WHEN GZM.kelompok = 'pegawai' THEN
				CONCAT('_',GZB.nama_barang,' (Pegawai)')	
			WHEN GZM.kelompok = 'pasien' THEN
				CONCAT('_',GZB.nama_barang,' (Pasien)')
			END
		) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		GZB.satuan
	FROM
		simrs.t_gudang_gizi_master GZM
	INNER JOIN simrs.t_gudang_gizi GZ ON GZ.id_trx = GZM.id_trx
	INNER JOIN simrs.m_supgudang_gizi SP ON SP.id_sup = GZM.id_sup
	INNER JOIN simrs.m_gudang_gizi_barang GZB ON GZB.kode_barang = GZ.kode_barang
	WHERE
		GZM.tgl BETWEEN '{$tglAwal}'
	AND '{$tglAkhir}'
	AND GZM.id_sup > 0
	AND GZM.ruangan = ''
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
		DATE_FORMAT(GZM.tgl, '%Y') AS thn_ang,
		CONCAT(
			'{$kdLokasi}',
			DATE_FORMAT(GZM.tgl, '%Y'),
			LPAD(
				DATE_FORMAT(GZM.tgl, '%y%m'),
				5,
				'0'
			),
			'K'
		) AS nodok,
		NOW() AS tgldok,
		GZM.tgl AS tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(
				(
					CASE
					WHEN GZM.kelompok = 'pegawai' THEN
						CONCAT(1, GZ.kode_barang)	
					WHEN GZM.kelompok = 'pasien' THEN
						CONCAT(2, GZ.kode_barang)	
					END
				),
				{$panjangKode},
				'0'
			)
		) AS kd_brg,
		SUM(GZ.jumlah) AS kuantitas,
		CONCAT(
				'SUMMARY',
				' ',
				'PENGELUARAN',
				' ',
				DATE_FORMAT(GZM.tgl, '%Y-%m'),
				' ',
				GZB.nama_barang
			) AS keterangan,		
		GZM.no_faktur AS nobukti,
		GZM.ruangan AS asal,
		'K01' AS jns_trn,
		GZ.harga AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		'' AS akun,
		LPAD(
			(
				CASE
				WHEN GZM.kelompok = 'pegawai' THEN
					CONCAT(1, GZ.kode_barang)	
				WHEN GZM.kelompok = 'pasien' THEN
					CONCAT(2, GZ.kode_barang)	
				END
			),
			'{$panjangKode}',
			'0'
		) AS kd_brg2,
		(
			CASE
			WHEN GZM.kelompok = 'pegawai' THEN
				CONCAT('_',GZB.nama_barang,' (Pegawai)')	
			WHEN GZM.kelompok = 'pasien' THEN
				CONCAT('_',GZB.nama_barang,' (Pasien)')
			END
		) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		GZB.satuan,
		GZ.kode_barang AS kode_barangx,
		GZM.kelompok AS kelompokx,
		DATE_FORMAT(GZM.tgl, '%m') AS bulanx,
		DATE_FORMAT(GZM.tgl, '%Y') AS tahunx
	FROM
		simrs.t_gudang_gizi_master GZM
	INNER JOIN simrs.t_gudang_gizi GZ ON GZ.id_trx = GZM.id_trx
	INNER JOIN simrs.m_gudang_gizi_barang GZB ON GZB.kode_barang = GZ.kode_barang
	WHERE
		GZM.tgl BETWEEN '{$tglAwal}'
	AND '{$tglAkhir}'
	AND GZM.id_sup = 0
	AND GZM.ruangan <> ''
	AND GZ.jumlah > 0
	GROUP BY
		DATE_FORMAT(GZM.tgl, '%Y%m'),
		GZB.kode_barang,
		GZM.kelompok
";

$rsKeluar = $mysql_2_13->query($sqlKeluar);

while($rowKeluar = $rsKeluar->fetch_object()) {
	$bulan = $rowKeluar->bulanx - 1;
	
	if ($bulan == 0) {
		$bulan = 12; 
		$tahun = $rowKeluar->tahunx - 1;
	} else {
		$tahun = $rowKeluar->tahunx;
	}

	$sql_awal = "
		SELECT
			SUM(jumlah_akhir) AS stok_awal
		FROM
			simrs.m_gudang_gizi_stokakhirbulan_new
		WHERE kode_barang = '{$rowKeluar->kode_barangx}'
		AND bulan = '{$bulan}'
		AND tahun = '{$tahun}'
		AND kelompok = '{$rowKeluar->kelompokx}'
		ORDER BY 
			ID DESC
		LIMIT 1
	";

	$rs_awal = $mysql_2_13->query($sql_awal);
	$row_awal = $rs_awal->fetch_object();

	if (isset($row_awal->stok_awal) && $row_awal->stok_awal > 0) {
		$stok_awal = $row_awal->stok_awal;
	} else {
		$stok_awal = 0;
	}

	$sql_masuk = "
		SELECT
			SUM(GZ.jumlah) AS stok_masuk
		FROM
			simrs.t_gudang_gizi_master AS GZM
		INNER JOIN simrs.t_gudang_gizi AS GZ ON GZ.id_trx = GZM.id_trx
		WHERE
		GZM.tgl BETWEEN '{$tglAwal}'
		AND '{$tglAkhir}'
		AND GZM.id_sup > 0
		AND GZM.ruangan = ''
	";

	$rs_masuk = $mysql_2_13->query($sql_masuk);
	$row_masuk = $rs_masuk->fetch_object();

	if (isset($row_masuk->stok_masuk) && $row_masuk->stok_masuk > 0) {
		$stok_masuk = $row_masuk->stok_masuk;
	} else {
		$stok_masuk = 0;
	}

	if ($rowKeluar->kuantitas > ($stok_awal + $stok_masuk)) {
		$rowKeluar->kuantitas = ($stok_awal + $stok_masuk);
	}

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
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=gizi_{$tglAwalx}_{$tglAkhirx}.txt");		

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