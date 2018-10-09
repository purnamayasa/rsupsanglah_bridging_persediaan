<?php

$kdLokasi   	= '024042200415661002KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115199';
$kdKbrg      	= '1010401002';
$panjangKode 	= '20';

//masuk
/*$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(PG.TGL_TERIMA,'%Y') thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(PG.TGL_TERIMA,'%Y'),LPAD(DATE_FORMAT(PG.TGL_TERIMA,'%m%d'),5,0),'M') AS nodok,
		NOW() AS tgldok,
		PG.TGL_TERIMA AS tglbuku,
		CONCAT('{$kdKbrg}', LPAD(PB.kodebarang, '{$panjangKode}', '0')) AS kd_brg,
		PB.jmlterima AS kuantitas,
		CONCAT('PEMBELIAN',DATE_FORMAT(PG.TGL_TERIMA,'%Y')) AS keterangan,
		S.nama AS asal,
		PG.NO_FAKTUR AS nobukti,
		'M02' AS jns_trn,
		PB.hargafakturst AS rph_sat,
		'' rph_aset,
		'' flagkirim,
		'' akun,
		LPAD(PB.kodebarang, '{$panjangKode}', '0') AS kd_brg2,
		CONCAT('_',O.nama_dagang) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		O.satuan
	FROM
		simrs2012.t_penerimaan_barang PB
	INNER JOIN simrs2012.t_penerimaan_gudang PG ON PG.ID = PB.IDPENERIMAAN
	INNER JOIN simrs2012.t_supp S ON S.id = PG.TERIMA_DARI
	INNER JOIN simrs2012.m_obat O ON O.id = PB.kodebarang
	WHERE
		PG.TGL_TERIMA BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
	AND PG.KETERANGAN NOT LIKE '%***%'
	ORDER BY
		PG.TGL_TERIMA,
		PG.ID ASC, 
		S.nama ASC
";*/

$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(PG.TGL_TERIMA,'%Y') thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(PG.TGL_TERIMA,'%Y'),LPAD(DATE_FORMAT(PG.TGL_TERIMA,'%m%d'),5,0),'M') AS nodok,
		NOW() AS tgldok,
		PG.TGL_TERIMA AS tglbuku,
		CONCAT('{$kdKbrg}', LPAD(PB.kodebarang, '{$panjangKode}', '0')) AS kd_brg,
		PB.jmlterima AS kuantitas,
		CONCAT('PEMBELIAN',DATE_FORMAT(PG.TGL_TERIMA,'%Y')) AS keterangan,
		S.nama AS asal,
		PG.NO_FAKTUR AS nobukti,
		'M02' AS jns_trn,
		PB.hargafakturst AS rph_sat,
		'' rph_aset,
		'' flagkirim,
		'' akun,
		LPAD(PB.kodebarang, '{$panjangKode}', '0') AS kd_brg2,
		CONCAT('_',O.nama_dagang) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		O.satuan
	FROM
		simrs2012.t_penerimaan_barang PB
	INNER JOIN simrs2012.t_penerimaan_gudang PG ON PG.ID = PB.IDPENERIMAAN
	INNER JOIN simrs2012.t_supp S ON S.id = PG.TERIMA_DARI
	INNER JOIN simrs2012.m_obat O ON O.id = PB.kodebarang
	WHERE
		PG.TGL_TERIMA BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
	/*AND PB.jmlterima > 0*/
	ORDER BY
		PG.TGL_TERIMA
";

$rsMasuk = mysql_query($sqlMasuk,$connection);

$total = 0;

while($rowMasuk = mysql_fetch_object($rsMasuk)) {	
	if (empty($rowMasuk->kuantitas)) {
		$rowMasuk->kuantitas = 0;
	}
	
	if (empty($rowMasuk->rph_sat)) {
		$rowMasuk->rph_sat = 0;
	}
	
	if (is_null($rowMasuk->rph_sat)) {
		$rowMasuk->rph_sat = 0;
	}
	
	if ($rowMasuk->rph_sat == 'NaN') {
		$rowMasuk->rph_sat = 0;
	}
		
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

/*
//keluar pasien
$sqlKeluar = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(PA.tglkeluar, '%Y') AS thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(PA.tglkeluar, '%Y'),LPAD(DATE_FORMAT(PA.tglkeluar, '%y%m'),5,'0'),'K') AS nodok,
		NOW() AS tgldok,
		CONCAT(LAST_DAY(DATE_FORMAT(PA.tglkeluar,'%Y-%m-%d')),' 23:59:59') tglbuku,
		CONCAT('{$kdKbrg}',LPAD(PA.kodebarang, {$panjangKode}, '0')) AS kd_brg,
		SUM(PA.jmlkeluar) AS kuantitas,
		CONCAT('SUMMARY',' ','PENGELUARAN',' ',DATE_FORMAT(PA.tglkeluar, '%Y-%m'),' ',O.nama_obat) keterangan,
		'' As asal,
		CONCAT('PENGELUARAN',DATE_FORMAT(PA.tglkeluar, '%Y')) nobukti,
		'K01' AS jns_trn,
		'' AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		LPAD(PA.kodebarang, '{$panjangKode}', '0') AS kd_brg2,
		CONCAT('_',O.nama_obat) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		O.satuan
	FROM
		simrs2012.t_permintaan_apotek AS PA
	INNER JOIN simrs2012.m_obat AS O ON PA.kodebarang = O.kode_obat
	WHERE
		PA.tglkeluar BETWEEN '{$tglAwal}'
	AND '{$tglAkhir}'
	GROUP BY
		DATE_FORMAT(PA.tglkeluar, '%Y%m'),
		PA.kodebarang
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

//keluar langsung
$sqlKeluar = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(RD.TGL_MINTA, '%Y') AS thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(RD.TGL_MINTA, '%Y'),LPAD(DATE_FORMAT(RD.TGL_MINTA, '%y%m'),5,'0'),'K') AS nodok,
		NOW() AS tgldok,
		CONCAT(LAST_DAY(DATE_FORMAT(RD.TGL_MINTA, '%Y-%m-%d')),' 23:59:59') tglbuku,
		CONCAT('{$kdKbrg}',LPAD(RD.KDBARANG, {$panjangKode}, '0')) AS kd_brg,
		SUM(RD.QTY_DIBERI) AS kuantitas,
		CONCAT('SUMMARY',' ','PENGELUARAN',' ',DATE_FORMAT(RD.TGL_MINTA, '%Y-%m'),' ',O.nama_obat) keterangan,
		CONCAT('PENGELUARAN',DATE_FORMAT(RD.TGL_MINTA, '%Y')) nobukti,
		'' AS asal,
		'K01' AS jns_trn,
		'' AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		LPAD(RD.KDBARANG, '{$panjangKode}', '0') AS kd_brg2,
		CONCAT('_',O.nama_obat) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		O.satuan
	FROM
		simrs2012.x_request_depo AS RD
		INNER JOIN simrs.WardMaster AS WM ON RD.UNIT_TUJUAN = WM.WardID
		INNER JOIN simrs2012.m_obat AS O ON O.id = RD.KDBARANG
		WHERE
		RD.TGL_MINTA BETWEEN '{$tglAwal}'
		AND '{$tglAkhir}'
	GROUP BY
		DATE_FORMAT(RD.TGL_MINTA, '%Y%m'),
		RD.KDBARANG
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
*/