<?php

$kdLokasi   	= '024042200415661003KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115199';
$kdKbrg      	= '1010399999';
$panjangKode 	= '20';

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
	AND GD.jumlah_minta > 0
	AND GB.ID > 0
	AND GK.kelompok <> 'INVENTARIS'
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