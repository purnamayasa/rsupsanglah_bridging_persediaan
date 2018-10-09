<?php

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