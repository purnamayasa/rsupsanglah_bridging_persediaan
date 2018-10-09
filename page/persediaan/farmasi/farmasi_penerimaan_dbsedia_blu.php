<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
$tanggal_compare = isset($_POST['tanggal_compare']) ? $_POST['tanggal_compare'] : '';
$tabel = 'farmasi_penerimaan_dbsedia_blu_excel';
?>
<h3>Import Excel Farmasi Persediaan BLU</h3>
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
			<td><input type="submit" name="submit" value="Import" /> <input type="submit" name="submit" value="View" /> <input type="submit" name="submit" value="Compare" /> <input type="text" name="tanggal_compare" value="<?php echo $tanggal_compare; ?>" /></td>
		</tr>		
	</table>	
</form>
<?php
if (isset($_POST['submit']) && $_POST['submit'] == 'Import' && !empty($tahun) && !empty($bulan) && !empty($tanggal) && !empty($_FILES['filefarmasi']['name'])) {
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
			`tgl` = '{$tahun}-{$bulan}-{$tanggal}'
		AND `tahun` = '{$tahun}'
	";
	$mysql->query($sql);

	$sql = "
		CREATE TABLE IF NOT EXISTS `rsupsanglah`.`{$tabel}` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`tgl` date DEFAULT NULL,
			`tahun` int(11) DEFAULT NULL,
			`kd_lokasi` varchar(255) DEFAULT NULL,
			`kd_sskel` varchar(255) DEFAULT NULL,
			`kd_brg` varchar(255) DEFAULT NULL,
			`kd_brg2` int(11) DEFAULT NULL,
			`ur_brg` varchar(255) DEFAULT NULL,
			`harga` decimal(30,2) DEFAULT NULL,
			`jumlah` decimal(30,2) DEFAULT NULL,
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
			$tgl = "{$tahun}-{$bulan}-{$tanggal}";
			//$tahun = $rowData[0][1];
			$kd_lokasi = $rowData[0][3];
			$kd_sskel = $rowData[0][0];
			$kd_brg = $rowData[0][2];
			$ur_brg = $mysql->real_escape_string($rowData[0][13]);
			$jumlah = $rowData[0][10];
			$total = $rowData[0][11];
			if ($total == 0) {
				$harga = 0;
			} else {
				$harga = $total / $jumlah;
			}

			echo $sql = "
				INSERT INTO 
					`rsupsanglah`.`{$tabel}`
				(
					`tgl`,
					`tahun`,
					`kd_lokasi`,
					`kd_sskel`,
					`kd_brg`,
					`kd_brg2`,
					`ur_brg`,
					`harga`,
					`jumlah`
				)
				VALUES
				(
					'{$tgl}',
					'{$tahun}',
					'{$kd_lokasi}',
					'{$kd_sskel}',
					'{$kd_brg}',
					CAST(REPLACE(`kd_brg`, `kd_sskel`, '') AS UNSIGNED),
					'{$ur_brg}',
					'{$harga}',
					'{$jumlah}'
				)
			";
			echo '<hr>';

			$mysql->query($sql);
		}

		if (preg_match('/kd_sskel/i', $rowData[0][0])) {
			$start = true;
		}
	}
}

if (isset($_POST['submit']) && ($_POST['submit'] == 'View' || $_POST['submit'] == 'Compare') && !empty($tahun) && !empty($bulan) && !empty($tanggal)) {
	$tgl = "{$tahun}-{$bulan}-{$tanggal}";
	$sql = "
		SELECT
			`kd_brg`,
			`kd_brg2`,
			`ur_brg`,
			`harga`*`jumlah` AS `jumlah`
		FROM
			`rsupsanglah`.`{$tabel}`
		WHERE
			`tgl` = '{$tgl}'
		AND `kd_sskel` NOT IN ('1010401999', '1010301005')
	";

	$rs = $mysql->query($sql);
	$jumlah = 0;
	$jumlahc = 0;
	$selisih = 0;
	$selisih_bulan = intval($bulan) - 1;
	echo '<div id="divPrint">';
	echo '<table id="table" width="100%" border="1" cellpadding="0" cellspacing="0">';
	echo '<legend>'.'<h3>'.'Selisih '.month_name($selisih_bulan, 1).' '.$tahun.'</h3>'.'</legend>';
	echo '<tr>';
	echo '<th>kd_brg</th>';
	echo '<th>ur_brg</th>';
	echo '<th>total</th>';
	echo '<th>farmasi</th>';
	echo '</tr>';
	while ($row = $rs->fetch_object()) {
		echo '<tr>';
		echo '<td>'."'".$row->kd_brg.'</td>';
		echo '<td>'.$row->ur_brg.'</td>';
		echo '<td align="right">'.number_format($row->jumlah,2).'</td>';
		if ($_POST['submit'] == 'Compare') {
			$sqlc = "
				SELECT
					SUM(`harga`*`jumlah`) AS jumlah
				FROM 	
					`rsupsanglah`.`farmasi_stokopname_excel`
				WHERE 
					`tgl` = '{$_POST['tanggal_compare']}'
				AND `kode_obat` = '{$row->kd_brg2}'
			";	
			$rsc = $mysql->query($sqlc);
			$rowc = $rsc->fetch_object();
			if (number_format($row->jumlah,2) <> number_format($rowc->jumlah,2)) {
				echo '<td align="right" bgcolor="pink">'.number_format($rowc->jumlah,2).'</td>';
				$selisih = $selisih + ($rowc->jumlah - $row->jumlah);
			} else {
				echo '<td align="right">'.number_format($rowc->jumlah,2).'</td>';
			}
			$jumlahc = $jumlahc + $rowc->jumlah;
		}
		echo '</tr>';
		$jumlah = $jumlah + $row->jumlah;
	}
	echo '<tr>';
	echo '<td colspan="2">Jumlah</td>';
	echo '<td align="right">'.number_format($jumlah,2).'</td>';
	if ($_POST['submit'] == 'Compare') {
		echo '<td align="right">'.number_format($jumlahc,2).'</td>';
	}
	echo '</tr>';
	if ($_POST['submit'] == 'Compare') {
		echo '<tr>';
		echo '<td colspan="3">Selisih</td>';
		echo '<td align="right">'.number_format($selisih,2).'</td>';
		echo '</tr>';
	}
	echo '</table>';
	echo '</div>';
	echo '<button onClick="printElem('."'".'divPrint'."'".')">Print</button>';
	echo '<button onClick="tableToExcel('."'".'table'."',"."'".'BLU'."'".')">Excel</button>';
}
