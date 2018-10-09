<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$tabel = 'farmasi_penerimaan_tt_excel';
?>
<h3>Import Excel Farmasi Penerimaan TT</h3>
<form action="" method="post" enctype="multipart/form-data">
	<table>
		<tr>
			<td>Tahun</td>
			<td>:</td>
			<td>
				<select name="tahun">
					<option value="">-PILIH-</option>
					<?php $tahunx = date('Y'); ?>
					<?php for ($t = $tahunx - 5; $t <= $tahunx + 5; $t++) { ?>
					<option value="<?php echo $t; ?>" <?php echo ($tahun == $t) ? 'selected' : ''; ?>><?php echo $t; ?></option>
					<?php } ?>
				</select>				
			</td>
		</tr>
		<tr>
			<td>Bulan</td>
			<td>:</td>
			<td>
				<select name="bulan">
					<option value="">-PILIH-</option>
					<?php for ($b = 1; $b <= 12; $b++) { ?>
					<option value="<?php echo str_pad($b,2,0,STR_PAD_LEFT); ?>" <?php echo ($bulan == $b) ? 'selected' : ''; ?>><?php echo str_pad($b,2,0,STR_PAD_LEFT); ?></option>
					<?php } ?>
				</select>	
			</td>
		</tr>
		<tr>
			<td>File</td>
			<td>:</td>
			<td><input type="file" id="filefarmasi" name="filefarmasi" /></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td><input type="submit" name="submit" value="Import" /> <input type="submit" name="submit" value="View" /></td>
		</tr>		
	</table>	
</form>
<?php
if (isset($_POST['submit']) && !empty($tahun) && !empty($bulan) && !empty($_FILES['filefarmasi']['name'])) {
	$start = false;
	$target = basename($_FILES['filefarmasi']['name']) ;
	move_uploaded_file($_FILES['filefarmasi']['tmp_name'], $target);

	//  Read your Excel workbook
	try {
		$inputFileType = PHPExcel_IOFactory::identify($target);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($target);
	} catch(Exception $e) {
		die('Error loading file "'.pathinfo($target,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	
	//  Get worksheet dimensions
	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow(); 
	$highestColumn = $sheet->getHighestColumn();
	
	// Delete file
	unlink($target);

	$sql = "
		DELETE FROM
			`rsupsanglah`.`{$tabel}`
		WHERE
			`tahun` = '{$tahun}'
		AND `bulan` = '{$bulan}'
	";
	$mysql->query($sql);

	$sql = "
		CREATE TABLE IF NOT EXISTS `rsupsanglah`.`{$tabel}` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`tahun` varchar(4) DEFAULT NULL,
			`bulan` varchar(2) DEFAULT NULL,
			`tgljam` datetime DEFAULT NULL,
			`no_faktur` varchar(255) DEFAULT NULL,
			`nama_supplier` varchar(255) DEFAULT NULL,
			`kode_obat` int(11) DEFAULT NULL,
			`nama_obat` varchar(255) DEFAULT NULL,
			`satuan` varchar(255) DEFAULT NULL,
			`harga` decimal(30,2) DEFAULT NULL,
			`jumlah` decimal(30,2) DEFAULT NULL,
			`total` decimal(30,2) DEFAULT NULL,
			`keterangan` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
	";

	$mysql->query($sql);

	//  Loop through each row of the worksheet in turn
	for ($row = 1; $row <= $highestRow; $row++){ 
		//  Read a row of data into an array
		$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
		//  Insert row data array into your database of choice here
		
		//echo '<pre>';
		//var_dump($rowData);
		//echo '</pre>';				
		
		if ($start) {

			$tahun = $tahun;
			$bulan = $bulan;
			$tgljam = $rowData[0][1];
			$no_faktur = $rowData[0][4];
			$nama_supplier = $mysql->real_escape_string($rowData[0][3]);
			$kode_obat = $rowData[0][5];
			$nama_obat = $mysql->real_escape_string($rowData[0][6]);
			$satuan = null;
			$harga = $rowData[0][7];
			$jumlah = $rowData[0][8];
			$keterangan = $rowData[0][10];

			if ($jumlah == 0) {
				continue;
			}

			echo $sql = "
				INSERT INTO 
					`rsupsanglah`.`{$tabel}`
				(
					`tahun`,
					`bulan`,
					`tgljam`,
					`no_faktur`,
					`nama_supplier`,
					`kode_obat`,
					`nama_obat`,
					`satuan`,
					`harga`,
					`jumlah`,
					`total`,
					`keterangan`
				)
				VALUES
				(
					'{$tahun}',
					'{$bulan}',
					'{$tgljam}',
					'{$no_faktur}',
					'{$nama_supplier}',
					'{$kode_obat}',
					'{$nama_obat}',
					'{$satuan}',
					'{$harga}',
					'{$jumlah}',
					(harga * jumlah),
					'{$keterangan}'
				)
			";
			echo '<hr>';

			$mysql->query($sql);
		}		

		if (preg_match('/no/i', $rowData[0][0])) {
			$start = true;
		}
	}
}

if (isset($_POST['submit']) && $_POST['submit'] == 'View' && !empty($tahun) && !empty($bulan)) {
	$tgl_awal = "{$tahun}-{$bulan}-01 00:00:00";
	$tgl_akhir = "{$tahun}-{$bulan}-31 23:59:59";
	$sql = "
		SELECT
			`tgljam`,
			`kode_obat`,
			`nama_obat`,
			`harga`,
			`jumlah`,
			`total`,
			`no_faktur`
		FROM
			`rsupsanglah`.`{$tabel}`
		WHERE
			`tgljam` BETWEEN '{$tgl_awal}' AND '{$tgl_akhir}'
		ORDER BY
			`tgljam`
	";

	$rs = $mysql->query($sql);
	$jumlah = 0;
	$total = 0;
	$total_hitung = 0;
	echo '<table width="100%" border="1" cellpadding="0" cellspacing="0">';
	echo '<tr>';
	echo '<th>Tgl Jam</th>';
	echo '<th>Kode Obat</th>';
	echo '<th>Nama Obat</th>';
	echo '<th>Harga</th>';
	echo '<th>Jumlah</th>';
	echo '<th>Total</th>';
	echo '<th>Total Hitung</th>';
	echo '<th>Blu</th>';
	echo '</tr>';
	while ($row = $rs->fetch_object()) {
		$sql_blu = "
			SELECT
				kd_lokasi
			FROM
				`dbsedia10blu`.`t_sediam`
			WHERE
				`tglbuku` = '{$row->tgljam}'
			AND	`kd_lokasi` = '024042200415661002KD'
			AND `kd_brg` = CONCAT('1010401002', LPAD({$row->kode_obat}, '20', '0'))
			AND `kuantitas` = '{$row->jumlah}'
			AND `rph_sat` = '{$row->harga}'
			AND `nobukti` = '{$row->no_faktur}'
		";
		$rs_blu = $mysql_2_4_local_persediaan->query($sql_blu);
		echo '<tr>';
		echo '<td>'.$row->tgljam.'</td>';
		echo '<td>'.$row->kode_obat.'</td>';
		echo '<td>'.$row->nama_obat.'</td>';
		echo '<td align="right">'.number_format($row->harga,2).'</td>';
		echo '<td align="right">'.number_format($row->jumlah,2).'</td>';
		echo '<td align="right">'.number_format($row->total,2).'</td>';
		echo '<td align="right">'.number_format($row->harga * $row->jumlah,2).'</td>';
		if ($rs_blu->num_rows == 0) {
			echo '<td style="background-color: pink;">'.$rs_blu->num_rows.'</td>';
		} else {
			echo '<td>'.$rs_blu->num_rows.'</td>';
		}	
		echo '</tr>';
		$jumlah = $jumlah + $row->jumlah;
		$total = $total + $row->total;
		$total_hitung = $total_hitung + ($row->harga * $row->jumlah);
	}
	echo '<tr>';
	echo '<td colspan="4">Jumlah</td>';
	echo '<td align="right">'.number_format($jumlah,2).'</td>';
	echo '<td align="right">'.number_format($total,2).'</td>';
	echo '<td align="right">'.number_format($total_hitung,2).'</td>';
	echo '</tr>';
	echo '</table>';
}
