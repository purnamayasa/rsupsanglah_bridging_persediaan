<?php

ini_set('display_errors',1);

require_once 'inc_db.php';

$database    = 'dbsedia10';
$database    = 'dbsedia10blu';

$tglAwal     	= '2017-01-01 00:00:00';
$tglAkhir    	= '2017-12-31 23:59:59';
$kdLokasi   	= '024042200415661003KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115199';
$kdKbrg      	= '1010399999';
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
		CONCAT('1010399999', LPAD(GB.ID, {$panjangKode}, 0)) `kd_brg`,
		CONCAT('_', GB.nama) `ur_brg`,
		'{$kdPerk}' `kd_perk`,
		'{$kdKbrg}' `kd_kbrg`,
		LPAD(GB.ID, {$panjangKode}, 0) `kd_jbrg`,
		GS.satuan `satuan`,
		'{$kdLokasi}' `kd_lokasi`
	FROM
		simrs.m_gudang_non_medis_barang GB
	LEFT JOIN simrs.m_gudang_non_medis_kelompok GK ON GK.ID = GB.kelompok_ID
	LEFT JOIN simrs.m_gudang_non_medis_merek GM ON GM.ID = GB.merek_ID
	LEFT JOIN simrs.m_gudang_non_medis_satuan GS ON GS.ID = GB.satuan_ID
	WHERE
		(GB.aset IS NULL OR GB.aset = 0)
	ORDER BY
		GK.kelompok,
		GB.nama
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
$sqlMasuk = "
	SELECT
		'{$kdLokasi}' kd_lokasi,
		'' kd_lokasi2,
		DATE_FORMAT(GM.tgljam_masuk,'%Y') thn_ang,
		CONCAT(
			'{$kdLokasi}',
			DATE_FORMAT(GM.tgljam_masuk,'%Y'),
			LPAD(
				DATE_FORMAT(GM.tgljam_masuk,'%m%d'),
				5,
				0
			),
			'M'
		) nodok,
		NOW() tgldok,
		GM.tgljam_masuk tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(GB.ID, {$panjangKode}, 0)
		) kd_brg,
		GD.jumlah_masuk kuantitas,
		CONCAT('PEMBELIAN',DATE_FORMAT(GM.tgljam_masuk,'%Y')) keterangan,
		GP.nama asal,
		GM.no_faktur nobukti,
		'M02' jns_trn,
		GD.harga rph_sat,
		'' rph_aset,
		'' flagkirim,
		'' akun
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
		'{$kdLokasi}' kd_lokasi,
		'' kd_lokasi2,
		DATE_FORMAT(GMK.tgljam_minta, '%Y') thn_ang,
		CONCAT(
			'{$kdLokasi}',
			DATE_FORMAT(GMK.tgljam_minta, '%Y'),
			LPAD(
				DATE_FORMAT(GMK.tgljam_minta, '%y%m'),
				5,
				0
			),
			'K'
		) nodok,
		NOW() tgldok,
		CONCAT(
			LAST_DAY(
				DATE_FORMAT(
					GMK.tgljam_minta,
					'%Y-%m-%d'
				)
			),
			' 23:59:59'
		) tglbuku,
		CONCAT(
			'{$kdKbrg}',
			LPAD(GB.ID, {$panjangKode}, 0)
		) kd_brg,
		SUM(GD.jumlah_minta) kuantitas,
		CONCAT(
			'SUMMARY',
			' ',
			'PENGELUARAN',
			' ',
			DATE_FORMAT(GMK.tgljam_minta, '%Y-%m'),
			' ',
			GB.nama
		) keterangan,
		CONCAT(
			'PENGELUARAN',
			DATE_FORMAT(GMK.tgljam_minta, '%Y')
		) nobukti,
		'K01' jns_trn,
		'' rph_sat,
		'' rph_aset,
		'' flagkirim,
		'' akun
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