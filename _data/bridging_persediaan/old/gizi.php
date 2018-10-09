<?php

ini_set('display_errors',1);

require_once 'inc_db.php';

$database    = 'dbsedia10';
$database    = 'dbsedia10blu';

$tglAwal     	= '2017-01-01 00:00:00';
$tglAkhir    	= '2017-12-31 23:59:59';
$kdLokasi   	= '024042200415661001KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115111';
$kdKbrg      	= '1010799999';
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

//barang pegawai
$sqlBarang = "
	SELECT
	CONCAT(
		'{$kdKbrg}',
		LPAD(CONCAT(1,kode_barang), {$panjangKode}, '0')
	) AS kd_brg,
	CONCAT('_', CONCAT(nama_barang,' (Pegawai)')) AS ur_brg,
	'{$kdPerk}' AS kd_perk,
	'{$kdKbrg}' AS kd_kbrg,
	LPAD(CONCAT(1,kode_barang), {$panjangKode}, '0') AS kd_jbrg,
	satuan,
	'{$kdLokasi}' AS kd_lokasi
FROM
	simrs.m_gudang_gizi_barang
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

//barang pasien
$sqlBarang = "
	SELECT
	CONCAT(
		'{$kdKbrg}',
		LPAD(CONCAT(2,kode_barang), {$panjangKode}, '0')
	) AS kd_brg,
	CONCAT('_', CONCAT(nama_barang,' (Pasien)')) AS ur_brg,
	'{$kdPerk}' AS kd_perk,
	'{$kdKbrg}' AS kd_kbrg,
	LPAD(CONCAT(2,kode_barang), {$panjangKode}, '0') AS kd_jbrg,
	satuan,
	'{$kdLokasi}' AS kd_lokasi
FROM
	simrs.m_gudang_gizi_barang
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

//awal
$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		'2017' AS thn_ang,
		CONCAT(
			'{$kdLokasi}',
			'2017',
			LPAD('0101', 5, 0),
			'M'
		) AS nodok,
		'2017-01-01' AS tgldok,
		'2017-01-01' AS tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(
				(
					CASE
					WHEN kelompok = 'pegawai' THEN
						CONCAT(1, kode_barang)	
					WHEN kelompok = 'pasien' THEN
						CONCAT(2, kode_barang)	
					END
				),
				{$panjangKode},
				'0'
			)
		) AS kd_brg,
		jumlah_akhir AS kuantitas,
		'SALDO AWAL 2017' AS keterangan,
		'GUDANG GIZI' AS asal,
		'SALDOAWAL2017' AS nobukti,
		'M01' AS jns_trn,
		harga AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim
	FROM
		m_gudang_gizi_stokakhirbulan
	WHERE
		`bulan` = '12'
	AND `tahun` = '2016'
";

$rsMasuk = mysql_query($sqlMasuk,$connection);

while($rowMasuk = mysql_fetch_object($rsMasuk)) {
	var_dump($rowMasuk);

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
				'{$rowMasuk->tglbuku}',
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

//masuk
$sqlMasuk = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(GZM.tgl,'%Y') AS thn_ang,
		CONCAT(
			'$kdLokasi',
			DATE_FORMAT(GZM.tgl,'%Y'),
			LPAD(
				DATE_FORMAT(GZM.tgl, '%m%d'),
				5,
				'0'
			),
			'M'
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
		GZ.jumlah AS kuantitas,
		CONCAT('PEMBELIAN',DATE_FORMAT(GZM.tgl,'%Y')) AS keterangan,
		SP.nama_sup AS asal,
		GZM.no_faktur AS nobukti,
		'M02' AS jns_trn,
		GZ.harga AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		'' AS akun
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
	var_dump($rowMasuk);

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
				'{$rowMasuk->tglbuku}',
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
		GZM.ruangan AS asal,
		GZM.no_faktur AS nobukti,
		'K01' AS jns_trn,
		GZ.harga AS rph_sat,
		'' AS rph_aset,
		'' AS flagkirim,
		'' AS akun
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
			AND	K.jns_trn = '{$rowKeluar->jns_trn}'
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