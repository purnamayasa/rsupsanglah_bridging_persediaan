<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
$table = 'non_medis_stokopname_excel';
?>
<h3>Import Excel Non Medis Stokopname</h3>
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
			<td>Tanggal</td>
			<td>:</td>
			<td>
				<select name="tanggal">
					<option value="">-PILIH-</option>
					<?php for ($tg = 1; $tg <= 31; $tg++) { ?>
					<option value="<?php echo str_pad($tg,2,0,STR_PAD_LEFT); ?>"  <?php echo ($tanggal == $tg) ? 'selected' : ''; ?>><?php echo str_pad($tg,2,0,STR_PAD_LEFT); ?></option>
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
if (isset($_POST['submit']) && $_POST['submit'] == 'Import' && !empty($tahun) && !empty($bulan) && !empty($tanggal) && !empty($_FILES['filefarmasi']['name'])) {
	$start = false;
	$keterangan = '';	
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
			`rsupsanglah`.`{$table}`
		WHERE
			tgl = '{$tahun}-{$bulan}-{$tanggal}'
	";
	$mysql->query($sql);

	$sql = "
		CREATE TABLE IF NOT EXISTS `rsupsanglah`.`{$table}` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`tgl` date DEFAULT NULL,
			`kode_barang` int(11) DEFAULT NULL,
			`nama_barang` varchar(255) DEFAULT NULL,
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

		if (preg_match('/00:00:00/i', $rowData[0][0]) || preg_match('/23:59:59/i', $rowData[0][0])) {
			$keterangan = $rowData[0][0];
		}			
		
		if ($start) {

			$kode_barang = $rowData[0][1];
			$nama_barang = $rowData[0][2];
			$satuan = $rowData[0][4];
			$harga = $rowData[0][6];
			$jumlah = $rowData[0][11];

			echo $sql = "
				INSERT INTO 
					`rsupsanglah`.`{$table}`
				(
					`tgl`,
					`kode_barang`,
					`nama_barang`,
					`satuan`,
					`harga`,
					`jumlah`,
					`total`,
					`keterangan`
				)
				VALUES
				(
					'{$tahun}-{$bulan}-{$tanggal}',
					'{$kode_barang}',
					'{$nama_barang}',
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

if (isset($_POST['submit']) && $_POST['submit'] == 'View' && !empty($tahun) && !empty($bulan) && !empty($tanggal)) {
	$tgl = "{$tahun}-{$bulan}-{$tanggal}";
	$sql = "
		SELECT
			`kode_barang`,
			`nama_barang`,
			`harga`,
			SUM(`jumlah`) AS `jumlah`,
			SUM(`total`) AS `total`
		FROM
			`rsupsanglah`.`{$table}`
		WHERE
			`tgl` = '{$tgl}'
		GROUP BY
			`kode_barang`
		ORDER BY
			`kode_barang`
	";

	$rs = $mysql->query($sql);
	$jumlah = 0;
	$total = 0;
	$total_hitung = 0;
	echo '<table width="100%" border="1" cellpadding="0" cellspacing="0">';
	echo '<tr>';
	echo '<th>Kode Barang</th>';
	echo '<th>Nama Barang</th>';
	echo '<th>Harga</th>';
	echo '<th>Jumlah</th>';
	echo '<th>Total</th>';
	echo '<th>Total Hitung</th>';
	echo '</tr>';
	while ($row = $rs->fetch_object()) {
		echo '<tr>';
		echo '<td>'.$row->kode_barang.'</td>';
		echo '<td>'.$row->nama_barang.'</td>';
		echo '<td align="right">'.number_format($row->harga,2).'</td>';
		echo '<td align="right">'.number_format($row->jumlah,2).'</td>';
		echo '<td align="right">'.number_format($row->total,2).'</td>';
		echo '<td align="right">'.number_format($row->harga * $row->jumlah,2).'</td>';
		echo '</tr>';
		$jumlah = $jumlah + $row->jumlah;
		$total = $total + $row->total;
		$total_hitung = $total_hitung + ($row->harga * $row->jumlah);
	}
	echo '<tr>';
	echo '<td colspan="3">Jumlah</td>';
	echo '<td align="right">'.number_format($jumlah,2).'</td>';
	echo '<td align="right">'.number_format($total,2).'</td>';
	echo '<td align="right">'.number_format($total_hitung,2).'</td>';
	echo '</tr>';
	echo '</table>';
}
