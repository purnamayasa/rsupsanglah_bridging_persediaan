<?php

$kdLokasi   	= '024042200415661003KD';
$kdLokasiInduk 	= '024042200415661000KD';
$kdPerk      	= '115199';
$kdKbrg      	= '1010399999';
$panjangKode 	= '20';

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
	AND GD.jumlah_masuk > 0
	AND GB.ID > 0
	AND GK.kelompok <> 'INVENTARIS'
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