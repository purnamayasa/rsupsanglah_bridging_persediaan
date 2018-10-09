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
?>

<table>
	<tr>
		<th>Kode Obat</th>
		<th>Nama Obat</th>
	</tr>
	<?php
	$sqlBarang = "
		SELECT
			M.kode_obat,
			M.nama_obat
		FROM
			simrs2012.m_obat M
		WHERE
			M.flag >= '0'
	";

	$rsBarang = mysql_query($sqlBarang,$connection);
	?>
	<?php while($rowBarang = mysql_fetch_object($rsBarang)) { ?>
	<tr>
		<td><?php echo $rowBarang->kode_obat; ?></td>
		<td><?php echo $rowBarang->nama_obat; ?></td>
	</tr>
	<?php } ?>
</table>