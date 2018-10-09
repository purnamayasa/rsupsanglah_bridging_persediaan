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
?>

<?php
header("Content-Type: application/ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=saldoawal_gizi_{$thnAng}.xls");
?>

<table width="100%" border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>kd_lokasi</th>
		<th>kd_lokasi2</th>
		<th>thn_ang</th>
		<th>nodok</th>
		<th>tgldok</th>
		<th>tglbuku</th>
		<th>kd_brg</th>
		<th>kuantitas</th>
		<th>keterangan</th>
		<th>asal</th>
		<th>nobukti</th>
		<th>jns_trn</th>
		<th>rph_sat</th>
		<th>rph_aset</th>
		<th>flagkirim</th>
		<th>akun</th>
	</tr>
<?php while($rowMasuk = mysql_fetch_object($rsMasuk)) { ?>
	<tr>
		<td><?php echo $rowMasuk->kd_lokasi; ?></td>
		<td><?php echo $rowMasuk->ur_brg2; ?></td>
		<td><?php echo $rowMasuk->thn_ang; ?></td>
		<td><?php echo $rowMasuk->nodok; ?></td>
		<td><?php echo "'".$rowMasuk->tgldok; ?></td>
		<td><?php echo "'".$rowMasuk->tglbuku; ?></td>
		<td><?php echo "'".$rowMasuk->kd_kbrg2.$rowMasuk->kd_brg2; ?></td>
		<td><?php echo number_format($rowMasuk->kuantitas, 2, '.', ''); ?></td>
		<td><?php echo $rowMasuk->satuan; ?></td>
		<td><?php echo $rowMasuk->asal; ?></td>
		<td><?php echo $rowMasuk->nobukti; ?></td>
		<td><?php echo $rowMasuk->jns_trn; ?></td>
		<td><?php echo number_format($rowMasuk->rph_sat, 2, '.', ''); ?></td>
		<td><?php echo number_format($rowMasuk->kuantitas * $rowMasuk->rph_sat, 2, '.', ''); ?></td>
		<td><?php echo $rowMasuk->flagkirim; ?></td>
		<td><?php echo ''; ?></td>
	</tr>
<?php } ?>
</table>