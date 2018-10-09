<?php

ini_set('display_errors',1);

require_once '../inc_db.php';
require_once 'class_inv_medis.php';

$inv_medis = new inv_medis($connection);

$kd_obat      = 620;
$tgljam_awal  = '2013-05-01';
$tgljam_akhir = '2017-05-26';
?>

<table>
	<tr>
		<th>Tanggal</th>
		<th>Kode Obat</th>
		<th>Nama Obat</th>
		<th>Awal</th>
		<th>Masuk</th>
		<th>Keluar</th>
		<th>Sisa</th>
	</tr>
	<?php while (strtotime($tgljam_awal) <= strtotime($tgljam_akhir)) { ?>
	<tr>
		<td><?php echo $tgljam_awal;?></td>
		<td>Kode Obat</td>
		<td>Nama Obat</td>
		<td><?php echo $inv_medis->stok_akhir_sebelumnya($kd_obat, $tgljam_awal.' 00:00:00'); ?></td>
		<td><?php echo $inv_medis->masuk($kd_obat, $tgljam_awal.' 00:00:00', $tgljam_awal.' 23:59:59'); ?></td>
		<td><?php echo $inv_medis->keluar($kd_obat, $tgljam_awal.' 00:00:00', $tgljam_awal.' 23:59:59'); ?></td>
		<td>
			<?php
			$tgl_besok = date("Y-m-d", strtotime("+1 day", strtotime($tgljam_awal)));
			echo $inv_medis->stok_sekarang($kd_obat, $tgl_besok.' 00:00:00', $tgl_besok.' 23:59:59'); 
			?>
		</td>
	</tr>
	<?php $tgljam_awal = date("Y-m-d", strtotime("+1 day", strtotime($tgljam_awal))); ?>
	<?php } ?>
</table>