<?php

ini_set('display_errors',1);

require_once 'inc_db.php';

/*$sql = "
	SELECT
		'2017-01-31 23:59:59' AS tgljam,
		S.kode_barang,
		S.nama_dagang,
		S.satuan,
		S.harga,
		(
			SELECT
				CASE
					WHEN SUM(jumlah) IS NULL THEN
						0
					ELSE
						SUM(jumlah)
					END
			FROM
				simrs2012.2017_stokopname
			WHERE
				tgljam = '2016-12-31 23:59:59'
			AND kode_barang = S.kode_barang
		) AS awal,
		(
			SELECT
				(
					CASE
					WHEN SUM(jumlah) IS NULL THEN
						0
					ELSE
						SUM(jumlah)
					END
				)
			FROM
				simrs2012.2017_penerimaan
			WHERE
				tgljam BETWEEN '2017-01-01 00:00:00'
			AND '2017-01-31 23:59:59'
			AND kode_barang = S.kode_barang
		) AS masuk,
		'' AS keluar,
		SUM(S.jumlah) AS akhir
	FROM
		simrs2012.2017_stokopname S
	WHERE
		S.tgljam = '2017-01-31 23:59:59'
	GROUP BY
		S.kode_barang
";*/

$sql = "
	SELECT
		'2017-09-30 23:59:59' AS tgljam,
		S.id AS kode_barang,
		S.nama_dagang,
		S.satuan,
		(
			SELECT
				(
					CASE
					WHEN harga IS NULL THEN
						0
					ELSE
						harga
					END
				)
			FROM
				simrs2012.2017_stokopname
			WHERE
				tgljam = '2017-09-30 23:59:59'
			AND kode_barang = S.id
			AND keterangan LIKE '%STOK OPNAME 2017 SEPTEMBER%'
			AND posting = 1
			LIMIT 1
		) AS harga,
		(
			SELECT
				(
					CASE
					WHEN SUM(jumlah) IS NULL THEN
						0
					ELSE
						SUM(jumlah)
					END
				)
			FROM
				simrs2012.2017_stokopname
			WHERE
				tgljam = '2017-08-31 23:59:59'
			AND kode_barang = S.id
			AND keterangan LIKE '%STOK OPNAME 2017 AGUSTUS%'
			AND posting = 1
		) AS awal,
		(
			SELECT
				(
					CASE
					WHEN SUM(PB.jmlterima) IS NULL THEN
						0
					ELSE
						SUM(PB.jmlterima)
					END
				)
			FROM
				simrs2012.t_penerimaan_barang PB
			INNER JOIN simrs2012.t_penerimaan_gudang PG ON PG.ID = PB.IDPENERIMAAN
			WHERE
				PG.TGL_TERIMA BETWEEN '2017-09-01 00:00:00'
			AND '2017-09-30 23:59:59'
			AND PB.kodebarang = S.id
			/*AND PB.jmlterima > 0*/
		) AS masuk,
		'' AS keluar,
		(
			SELECT
				(
					CASE
					WHEN SUM(jumlah) IS NULL THEN
						0
					ELSE
						SUM(jumlah)
					END
				)
			FROM
				simrs2012.2017_stokopname
			WHERE
				tgljam = '2017-09-30 23:59:59'
			AND kode_barang = S.id
			AND keterangan LIKE '%STOK OPNAME 2017 SEPTEMBER%'
			AND posting = 1
		) AS akhir
	FROM
		simrs2012.m_obat S
";

$rs = mysql_query($sql,$connection);
?>

<?php
header("Content-Type: application/ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=medis_keluar_092017.xls");
?>

<table width="100%" border="1" cellpadding="0" cellspacing="0">
	<tr>
		<th>tgljam</th>
		<th>kode_barang</th>
		<th>nama_dagang</th>
		<th>satuan</th>
		<th>harga</th>
		<th>awal</th>
		<th>masuk</th>
		<th>keluar</th>
		<th>akhir</th>
	</tr>
<?php while($row = mysql_fetch_object($rs)) { ?>
	<?php
	if (empty($row->harga)) {
		$row->harga = 0;
	}
	
	if (is_null($row->harga)) {
		$row->harga = 0;
	}
	?>
	<tr>
		<td><?php echo $row->tgljam; ?></td>
		<td><?php echo $row->kode_barang; ?></td>
		<td><?php echo $row->nama_dagang; ?></td>
		<td><?php echo $row->satuan; ?></td>
		<td><?php echo $row->harga; ?></td>
		<td><?php echo $row->awal; ?></td>
		<td><?php echo $row->masuk; ?></td>
		<td><?php echo $row->keluar; ?></td>
		<td><?php echo $row->akhir; ?></td>
	</tr>
<?php } ?>
</table>