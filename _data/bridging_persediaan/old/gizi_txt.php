<?php

ini_set('display_errors',1);

require_once 'inc_db.php';

$tglAwal     	= '2017-01-01 00:00:00';
$tglAkhir    	= '2017-12-31 23:59:59';
$kdLokasi   	= '024042200415661001KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115111';
$kdKbrg      	= '1010799999';
$panjangKode 	= '20';

$posts  		= array();

//awal
$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		'2017' AS thn_ang,
		CONCAT('{$kdLokasi}','2017',LPAD('0101', 5, 0),'M') AS nodok,
		'2017-01-01' AS tgldok,
		'2017-01-01' AS tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(
				(
					CASE
					WHEN kelompok = 'pegawai' THEN
						CONCAT(1, S.kode_barang)	
					WHEN kelompok = 'pasien' THEN
						CONCAT(2, S.kode_barang)	
					END
				),
				'{$panjangKode}',
				'0'
			)
		) AS kd_brg,
		S.jumlah_akhir AS kuantitas,
		'SALDO AWAL 2017' AS keterangan,
		'GUDANG GIZI' AS asal,
		'SALDOAWAL2017' AS nobukti,
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
		CONCAT('_',O.nama_barang) AS ur_brg2,
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
		CONCAT('_',GZB.nama_barang) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		GZB.satuan
	FROM
		t_gudang_gizi_master GZM
	INNER JOIN t_gudang_gizi GZ ON GZ.id_trx = GZM.id_trx
	INNER JOIN m_supgudang_gizi SP ON SP.id_sup = GZM.id_sup
	INNER JOIN m_gudang_gizi_barang GZB ON GZB.kode_barang = GZ.kode_barang
	WHERE
		GZM.tgl BETWEEN '{$tglAwal}'
	AND '{$tglAkhir}'
	AND GZM.id_sup > 0
	AND GZM.ruangan = ''
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
		CONCAT('_',GZB.nama_barang) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		GZB.satuan
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