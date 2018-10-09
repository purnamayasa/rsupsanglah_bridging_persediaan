<?php

ini_set('display_errors',1);

require_once 'inc_db.php';

$database    = 'dbsedia10';
$database    = 'dbsedia10blu';

$tglAwal     	= '2017-01-01 00:00:00';
$tglAkhir    	= '2017-12-31 23:59:59';
$kdLokasi   	= '024042200415661002KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115199';
$kdKbrg      	= '1010401002';
$panjangKode 	= '6';
$panjangKode 	= '20';

//bersihkan data
$sqlHapus = "
	DELETE FROM
		{$database}.t_brg
	WHERE
		kd_lokasi = '{$kdLokasi}'
";

$rsHapus = mysql_query($sqlHapus,$connection4);

$sqlHapus = "
	DELETE FROM
		{$database}.t_sediam
	WHERE
		tglbuku >= '{$tglAwal}'
	AND kd_lokasi = '{$kdLokasi}'
	AND jns_trn = 'M02'
";

$rsHapus = mysql_query($sqlHapus,$connection4);

$sqlHapus = "
	DELETE FROM
		{$database}.t_sediak
	WHERE
		tglbuku >= '{$tglAwal}'
	AND kd_lokasi = '{$kdLokasi}'
";

$rsHapus = mysql_query($sqlHapus,$connection4);

//barang
$sqlBarang = "
	SELECT
		M.kode_obat AS id,
		CONCAT('{$kdKbrg}', LPAD(M.kode_obat, {$panjangKode}, '0')) AS kd_brg,
		CONCAT('_', M.nama_obat) AS ur_brg,
		'{$kdPerk}' AS kd_perk,
		'{$kdKbrg}' AS kd_kbrg,
		LPAD(M.kode_obat, {$panjangKode}, '0') AS kd_jbrg,
		M.satuan,
		'{$kdLokasi}' AS kd_lokasi
	FROM
		simrs2012.m_obat M
	WHERE
		M.flag >= '0'
";

$rsBarang = mysql_query($sqlBarang,$connection);

while($rowBarang = mysql_fetch_object($rsBarang)) {
	var_dump($rowBarang);	
	
	$rowBarang->ur_brg = mysql_real_escape_string($rowBarang->ur_brg);
	
	$sqlCekBarang = "
		SELECT
			*
		FROM	
			{$database}.t_brg AS B
		WHERE
			B.kd_brg = '{$rowBarang->kd_brg}'
		AND	B.kd_perk =  '{$rowBarang->kd_perk}'
		AND	B.kd_kbrg = '{$rowBarang->kd_kbrg}'
		AND	B.kd_jbrg = '{$rowBarang->kd_jbrg}'
		AND	B.kd_lokasi = '{$rowBarang->kd_lokasi}'
	";
	
	echo '<br>'.$sqlCekBarang.'<br>';
	
	$rsCekBarang = mysql_query($sqlCekBarang,$connection4);
	
	if (mysql_num_rows($rsCekBarang) > 0 ) {
		echo 'update';
		$sqlQuery = "
			UPDATE 
				{$database}.t_brg B
			SET
				B.ur_brg =  '{$rowBarang->ur_brg}',
				AND	B.satuan =  '{$rowBarang->satuan}'
			WHERE
				B.kd_brg = '{$rowBarang->kd_brg}'
			AND	B.kd_perk =  '{$rowBarang->kd_perk}'
			AND	B.kd_kbrg = '{$rowBarang->kd_kbrg}'
			AND	B.kd_jbrg = '{$rowBarang->kd_jbrg}'
			AND	B.kd_lokasi = '{$rowBarang->kd_lokasi}'
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	} else {
		echo 'insert';
		$sqlQuery = "
			INSERT INTO
				{$database}.t_brg
			(
				kd_brg,
				ur_brg,
				kd_perk,
				kd_kbrg,
				kd_jbrg,
				satuan,
				kd_lokasi
			)
			VALUES
			(
				'{$rowBarang->kd_brg}',
				'{$rowBarang->ur_brg}',
				'{$rowBarang->kd_perk}',
				'{$rowBarang->kd_kbrg}',
				'{$rowBarang->kd_jbrg}',
				'{$rowBarang->satuan}',
				'{$rowBarang->kd_lokasi}'
			)
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	}
	
	//induk
	$sqlCekBarang = "
		SELECT
			B.kd_brg,
			B.ur_brg,
			B.kd_perk,
			B.kd_kbrg,
			B.kd_jbrg,
			B.satuan,
			B.kd_lokasi
		FROM	
			{$database}.t_brg AS B
		WHERE
			B.kd_brg = '{$rowBarang->kd_brg}'
		AND	B.kd_perk =  '{$rowBarang->kd_perk}'
		AND	B.kd_kbrg = '{$rowBarang->kd_kbrg}'
		AND	B.kd_jbrg = '{$rowBarang->kd_jbrg}'
		AND	B.kd_lokasi = '{$kdLokasiInduk}'
	";
	
	echo '<br>'.$sqlCekBarang.'<br>';
	
	$rsCekBarang = mysql_query($sqlCekBarang,$connection4);
	
	if (mysql_num_rows($rsCekBarang) > 0 ) {
		echo 'update';
		$sqlQuery = "
			UPDATE 
				{$database}.t_brg B
			SET
				B.ur_brg =  '{$rowBarang->ur_brg}',
				B.satuan =  '{$rowBarang->satuan}'
			WHERE
				B.kd_brg = '{$rowBarang->kd_brg}'
			AND	B.kd_perk =  '{$rowBarang->kd_perk}'
			AND	B.kd_kbrg = '{$rowBarang->kd_kbrg}'
			AND	B.kd_jbrg = '{$rowBarang->kd_jbrg}'
			AND	B.kd_lokasi = '{$kdLokasiInduk}'
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	} else {
		echo 'insert';
		$sqlQuery = "
			INSERT INTO
				{$database}.t_brg
			(
				kd_brg,
				ur_brg,
				kd_perk,
				kd_kbrg,
				kd_jbrg,
				satuan,
				kd_lokasi
			)
			VALUES
			(
				'{$rowBarang->kd_brg}',
				'{$rowBarang->ur_brg}',
				'{$rowBarang->kd_perk}',
				'{$rowBarang->kd_kbrg}',
				'{$rowBarang->kd_jbrg}',
				'{$rowBarang->satuan}',
				'{$kdLokasiInduk}'
			)
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	}
	echo '<hr>';
}

//masuk
/*$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(PG.TGL_TERIMA,'%Y') thn_ang,
		CONCAT(
			'{$kdLokasi}',
			DATE_FORMAT(PG.TGL_TERIMA,'%Y'),
			LPAD(
				DATE_FORMAT(PG.TGL_TERIMA,'%m%d'),
				5,
				0
			),
			'M'
		) AS nodok,
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
		'' akun
	FROM
		simrs2012.t_penerimaan_gudang PG
	INNER JOIN simrs2012.t_penerimaan_barang PB ON PB.IDPENERIMAAN = PG.ID
	INNER JOIN simrs2012.m_obat O ON O.kode_obat = PB.kodebarang
	LEFT JOIN simrs2012.t_supp S ON S.id = PG.TERIMA_DARI
	WHERE
		PG.TGL_TERIMA BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
	AND PB.jmlterima > 0
	AND PB.hargafakturst > 0
	AND O.flag >= '0'
	AND O.catagory <> 'ALAT KESEHATAN'
	AND O.catagory <> 'ALAT KESEHATAN PJT'
	ORDER BY
		PG.TGL_TERIMA
";*/

$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(PG.TGL_TERIMA,'%Y') thn_ang,
		CONCAT(
			'{$kdLokasi}',
			DATE_FORMAT(PG.TGL_TERIMA,'%Y'),
			LPAD(
				DATE_FORMAT(PG.TGL_TERIMA,'%m%d'),
				5,
				0
			),
			'M'
		) AS nodok,
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
		'' akun
	FROM
		simrs2012.t_penerimaan_gudang PG
	INNER JOIN simrs2012.t_penerimaan_barang PB ON PB.IDPENERIMAAN = PG.ID
	INNER JOIN simrs2012.m_obat O ON O.kode_obat = PB.kodebarang
	LEFT JOIN simrs2012.t_supp S ON S.id = PG.TERIMA_DARI
	WHERE
		PG.TGL_TERIMA BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
	AND PB.jmlterima > 0
	AND PB.hargafakturst > 0
	AND O.flag >= '0'
	AND PG.KETERANGAN NOT LIKE '%***%'
	ORDER BY
		PG.TGL_TERIMA
";

$rsMasuk = mysql_query($sqlMasuk,$connection);

while($rowMasuk = mysql_fetch_object($rsMasuk)) {
	var_dump($rowMasuk);
	$waktuNow = date('H:i:s');

	$sqlCekMasuk = "
		SELECT
			*
		FROM	
			{$database}.t_sediam AS M
		WHERE
			M.kd_lokasi = '{$rowMasuk->kd_lokasi}'
		AND	M.kd_lokasi2 = '{$rowMasuk->kd_lokasi2}'
		AND	M.thn_ang = '{$rowMasuk->thn_ang}'
		AND	M.nodok = '{$rowMasuk->nodok}'
		AND	M.tglbuku = '{$rowMasuk->tglbuku}'
		AND	M.kd_brg = '{$rowMasuk->kd_brg}'
		AND	M.keterangan = '{$rowMasuk->keterangan}'
		AND	M.jns_trn = '{$rowMasuk->jns_trn}'
	";
	
	echo '<br>'.$sqlCekMasuk.'<br>';
	
	$rsCekMasuk = mysql_query($sqlCekMasuk,$connection4);
	
	if (mysql_num_rows($rsCekMasuk) > 0 ) {
		echo 'update';
		$sqlQuery = "
			UPDATE 
				{$database}.t_sediam AS M
			SET
				M.tglbuku = '{$rowMasuk->tglbuku}',
				M.kuantitas = '{$rowMasuk->kuantitas}',
				M.asal = '{$rowMasuk->asal}',
				M.nobukti = '{$rowMasuk->nobukti}',
				M.rph_sat = '{$rowMasuk->rph_sat}',
				M.rph_aset = '{$rowMasuk->rph_aset}',
				M.flagkirim = '{$rowMasuk->flagkirim}',
				M.akun = '{$rowMasuk->akun}'
			WHERE
				M.kd_lokasi = '{$rowMasuk->kd_lokasi}'
			AND	M.kd_lokasi2 = '{$rowMasuk->kd_lokasi2}'
			AND	M.thn_ang = '{$rowMasuk->thn_ang}'
			AND	M.nodok = '{$rowMasuk->nodok}'
			AND	M.tgldok = '{$rowMasuk->tgldok}'
			AND	M.kd_brg = '{$rowMasuk->kd_brg}'
			AND	M.keterangan = '{$rowMasuk->keterangan}'
			AND	M.jns_trn = '{$rowMasuk->jns_trn}'
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	} else {
		echo 'insert';
		$sqlQuery = "
			INSERT 
				INTO {$database}.t_sediam 
			(
				kd_lokasi,
				kd_lokasi2,
				thn_ang,
				nodok,
				tgldok,
				tglbuku,
				kd_brg,
				kuantitas,
				keterangan,
				asal,
				nobukti,
				jns_trn,
				rph_sat,
				rph_aset,
				flagkirim,
				akun
			)
			VALUES
			(
				'{$rowMasuk->kd_lokasi}',
				'{$rowMasuk->kd_lokasi2}',
				'{$rowMasuk->thn_ang}',
				'{$rowMasuk->nodok}',
				'{$rowMasuk->tgldok}',
				'{$rowMasuk->tglbuku} {$waktuNow}',
				'{$rowMasuk->kd_brg}',
				'{$rowMasuk->kuantitas}',
				'{$rowMasuk->keterangan}',
				'{$rowMasuk->asal}',
				'{$rowMasuk->nobukti}',
				'{$rowMasuk->jns_trn}',
				'{$rowMasuk->rph_sat}',
				'{$rowMasuk->rph_aset}',
				'{$rowMasuk->flagkirim}',
				'{$rowMasuk->akun}'
			)
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	}
	echo '<hr>';
}

//keluar pasien
$sqlKeluar = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(PA.tglkeluar, '%Y') AS thn_ang,
		CONCAT(
			'{$kdLokasi}',
			DATE_FORMAT(PA.tglkeluar, '%Y'),
			LPAD(
				DATE_FORMAT(PA.tglkeluar, '%y%m'),
				5,
				'0'
			),
			'K'
		) AS nodok,
		NOW() AS tgldok,
		CONCAT(
				LAST_DAY(
					DATE_FORMAT(
						PA.tglkeluar,
						'%Y-%m-%d'
					)
				),
				' 23:59:59'
			) tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(O.kode_obat, {$panjangKode}, '0')
		) AS kd_brg,
		SUM(PA.jmlkeluar) AS kuantitas,
		CONCAT(
				'SUMMARY',
				' ',
				'PENGELUARAN',
				' ',
				DATE_FORMAT(PA.tglkeluar, '%Y-%m'),
				' ',
				O.nama_obat
			) keterangan,
		CONCAT(
			'PENGELUARAN',
			DATE_FORMAT(PA.tglkeluar, '%Y')
		) nobukti,
		'K01' AS jns_trn,
		'' AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim
	FROM
		simrs2012.t_permintaan_apotek AS PA
	INNER JOIN simrs2012.m_obat AS O ON PA.kodebarang = O.kode_obat
	WHERE
		PA.tglkeluar BETWEEN '{$tglAwal}'
	AND '{$tglAkhir}'
	AND O.catagory <> 'ALAT KESEHATAN'
	AND O.catagory <> 'ALAT KESEHATAN PJT'
	AND PA.jmlkeluar > 0
	GROUP BY
		DATE_FORMAT(PA.tglkeluar, '%Y%m'),
		PA.kodebarang
";

$rsKeluar = mysql_query($sqlKeluar,$connection);

while($rowKeluar = mysql_fetch_object($rsKeluar)) {
	var_dump($rowKeluar);
	
	$sqlCekKeluar = "
		SELECT
			*
		FROM	
			{$database}.t_sediak AS K
		WHERE
			K.kd_lokasi = '{$rowKeluar->kd_lokasi}'
		AND	K.kd_lokasi2 = '{$rowKeluar->kd_lokasi2}'
		AND	K.thn_ang = '{$rowKeluar->thn_ang}'
		AND	K.nodok = '{$rowKeluar->nodok}'
		AND	K.tglbuku = '{$rowKeluar->tglbuku}'
		AND	K.kd_brg = '{$rowKeluar->kd_brg}'
		AND	K.keterangan = '{$rowKeluar->keterangan}'
		AND	K.jns_trn = '{$rowKeluar->jns_trn}'
	";
	
	echo '<br>'.$sqlCekKeluar.'<br>';
	
	$rsCekKeluar = mysql_query($sqlCekKeluar,$connection4);
	
	if (mysql_num_rows($rsCekKeluar) > 0 ) {
		echo 'update';
		$sqlQuery = "
			UPDATE 
				{$database}.t_sediak AS K
			SET
				K.tglbuku = '{$rowKeluar->tglbuku}',
				K.kuantitas = '{$rowKeluar->kuantitas}',
				K.nobukti = '{$rowKeluar->nobukti}',
				K.rph_sat = '{$rowKeluar->rph_sat}',
				K.rphaset = '{$rowKeluar->rph_aset}',
				K.flagkirim = '{$rowKeluar->flagkirim}'
			WHERE
				K.kd_lokasi = '{$rowKeluar->kd_lokasi}'
			AND	K.kd_lokasi2 = '{$rowKeluar->kd_lokasi2}'
			AND	K.thn_ang = '{$rowKeluar->thn_ang}'
			AND	K.nodok = '{$rowKeluar->nodok}'
			AND	K.tgldok = '{$rowKeluar->tgldok}'
			AND	K.kd_brg = '{$rowKeluar->kd_brg}'
			AND	K.keterangan = '{$rowKeluar->keterangan}'
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	} else {
		echo 'insert';
		$sqlQuery = "
			INSERT INTO 
				{$database}.t_sediak 
			(
				kd_lokasi,
				kd_lokasi2,
				thn_ang,
				nodok,
				tgldok,
				tglbuku,
				kd_brg,
				kuantitas,
				keterangan,
				nobukti,
				jns_trn,
				rph_sat,
				rphaset,
				flagkirim
			)
			VALUES
			(
				'{$rowKeluar->kd_lokasi}',
				'{$rowKeluar->kd_lokasi2}',
				'{$rowKeluar->thn_ang}',
				'{$rowKeluar->nodok}',
				'{$rowKeluar->tgldok}',
				'{$rowKeluar->tglbuku}',
				'{$rowKeluar->kd_brg}',
				'{$rowKeluar->kuantitas}',
				'{$rowKeluar->keterangan}',
				'{$rowKeluar->nobukti}',
				'{$rowKeluar->jns_trn}',
				'{$rowKeluar->rph_sat}',
				'{$rowKeluar->rph_aset}',
				'{$rowKeluar->flagkirim}'
			)
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	}
	echo '<hr>';
}

//keluar langsung
$sqlKeluar = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(RD.TGL_MINTA, '%Y') AS thn_ang,
		CONCAT(
			'{$kdLokasi}',
			DATE_FORMAT(RD.TGL_MINTA, '%Y'),
			LPAD(
				DATE_FORMAT(RD.TGL_MINTA, '%y%m'),
				5,
				'0'
			),
			'K'
		) AS nodok,
		NOW() AS tgldok,
		CONCAT(
			LAST_DAY(
				DATE_FORMAT(RD.TGL_MINTA, '%Y-%m-%d')
			),
			' 23:59:59'
		) tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(RD.KDBARANG, {$panjangKode}, '0')
		) AS kd_brg,
		SUM(RD.QTY_DIBERI) AS kuantitas,
		CONCAT(
			'SUMMARY',
			' ',
			'PENGELUARAN',
			' ',
			DATE_FORMAT(RD.TGL_MINTA, '%Y-%m'),
			' ',
			O.nama_obat
		) keterangan,
		CONCAT(
			'PENGELUARAN',
			DATE_FORMAT(RD.TGL_MINTA, '%Y')
		) nobukti,
		'K01' AS jns_trn,
		'' AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim
	FROM
		simrs2012.x_request_depo AS RD
		INNER JOIN simrs.WardMaster AS WM ON RD.UNIT_TUJUAN = WM.WardID
		INNER JOIN simrs2012.m_obat AS O ON O.id = RD.KDBARANG
		WHERE
		RD.TGL_MINTA BETWEEN '{$tglAwal}'
		AND '{$tglAkhir}'
		AND O.catagory <> 'ALAT KESEHATAN'
		AND O.catagory <> 'ALAT KESEHATAN PJT'
		AND RD.QTY_DIBERI > 0
	GROUP BY
		DATE_FORMAT(RD.TGL_MINTA, '%Y%m'),
		RD.KDBARANG
";

$rsKeluar = mysql_query($sqlKeluar,$connection);

while($rowKeluar = mysql_fetch_object($rsKeluar)) {
	var_dump($rowKeluar);
	
	$sqlCekKeluar = "
		SELECT
			*
		FROM	
			{$database}.t_sediak AS K
		WHERE
			K.kd_lokasi = '{$rowKeluar->kd_lokasi}'
		AND	K.kd_lokasi2 = '{$rowKeluar->kd_lokasi2}'
		AND	K.thn_ang = '{$rowKeluar->thn_ang}'
		AND	K.nodok = '{$rowKeluar->nodok}'
		AND	K.tglbuku = '{$rowKeluar->tglbuku}'
		AND	K.kd_brg = '{$rowKeluar->kd_brg}'
		AND	K.keterangan = '{$rowKeluar->keterangan}'
		AND	K.jns_trn = '{$rowKeluar->jns_trn}'
	";
	
	echo '<br>'.$sqlCekKeluar.'<br>';
	
	$rsCekKeluar = mysql_query($sqlCekKeluar,$connection4);
	
	if (mysql_num_rows($rsCekKeluar) > 0 ) {
		echo 'update';
		$sqlQuery = "
			UPDATE 
				{$database}.t_sediak AS K
			SET
				K.tglbuku = '{$rowKeluar->tglbuku}',
				K.kuantitas = '{$rowKeluar->kuantitas}',
				K.nobukti = '{$rowKeluar->nobukti}',
				K.rph_sat = '{$rowKeluar->rph_sat}',
				K.rphaset = '{$rowKeluar->rph_aset}',
				K.flagkirim = '{$rowKeluar->flagkirim}'
			WHERE
				K.kd_lokasi = '{$rowKeluar->kd_lokasi}'
			AND	K.kd_lokasi2 = '{$rowKeluar->kd_lokasi2}'
			AND	K.thn_ang = '{$rowKeluar->thn_ang}'
			AND	K.nodok = '{$rowKeluar->nodok}'
			AND	K.tgldok = '{$rowKeluar->tgldok}'
			AND	K.kd_brg = '{$rowKeluar->kd_brg}'
			AND	K.keterangan = '{$rowKeluar->keterangan}'
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	} else {
		echo 'insert';
		$sqlQuery = "
			INSERT INTO 
				{$database}.t_sediak 
			(
				kd_lokasi,
				kd_lokasi2,
				thn_ang,
				nodok,
				tgldok,
				tglbuku,
				kd_brg,
				kuantitas,
				keterangan,
				nobukti,
				jns_trn,
				rph_sat,
				rphaset,
				flagkirim
			)
			VALUES
			(
				'{$rowKeluar->kd_lokasi}',
				'{$rowKeluar->kd_lokasi2}',
				'{$rowKeluar->thn_ang}',
				'{$rowKeluar->nodok}',
				'{$rowKeluar->tgldok}',
				'{$rowKeluar->tglbuku}',
				'{$rowKeluar->kd_brg}',
				'{$rowKeluar->kuantitas}',
				'{$rowKeluar->keterangan}',
				'{$rowKeluar->nobukti}',
				'{$rowKeluar->jns_trn}',
				'{$rowKeluar->rph_sat}',
				'{$rowKeluar->rph_aset}',
				'{$rowKeluar->flagkirim}'
			)
		";
		$rsQuery = mysql_query($sqlQuery,$connection4);
		echo '<br>'.$sqlQuery.'<br>';
	}
	echo '<hr>';
}