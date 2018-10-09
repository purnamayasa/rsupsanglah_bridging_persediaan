<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
$depo = isset($_POST['depo']) ? $_POST['depo'] : '';
$table = 'farmasi_stokopname_excel';
?>
<h3>Import Excel Farmasi Stokopname</h3>
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
			<td>Depo</td>
			<td>:</td>
			<td>
				<select name="depo">
					<option value="">-PILIH-</option>
					<option value="95" <?php echo $depo == 95 ? 'selected' : ''; ?>>OK IBS</option>
					<option value="93" <?php echo $depo == 93 ? 'selected' : ''; ?>>IRD</option>
					<option value="99" <?php echo $depo == 99 ? 'selected' : ''; ?>>Kemoterapi</option>
					<option value="98" <?php echo $depo == 98 ? 'selected' : ''; ?>>Cath Lab</option>
					<option value="97" <?php echo $depo == 97 ? 'selected' : ''; ?>>OK Wing</option>
					<option value="94" <?php echo $depo == 94 ? 'selected' : ''; ?>>Rawat Jalan</option>
					<option value="100" <?php echo $depo == 100 ? 'selected' : ''; ?>>Wing</option>
					<option value="12" <?php echo $depo == 12 ? 'selected' : ''; ?>>Gudang Farmasi</option>
					<option value="96" <?php echo $depo == 96 ? 'selected' : ''; ?>>OK IRD</option>
					<option value="91" <?php echo $depo == 91 ? 'selected' : ''; ?>>Sentral</option>
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
if (isset($_POST['submit']) && $_POST['submit'] == 'Import' && !empty($tahun) && !empty($bulan) && !empty($tanggal) & !empty($depo) && !empty($_FILES['filefarmasi']['name'])) {
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
		AND depo_id = '{$depo}'
	";
	$mysql->query($sql);

	$sql = "
		CREATE TABLE IF NOT EXISTS `rsupsanglah`.`{$table}` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`tgl` date DEFAULT NULL,
			`depo_id` int(11) DEFAULT NULL,
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

		if (preg_match('/stok depo/i', $rowData[0][0])) {
			if (!preg_match('/'.$tahun.'/i', $rowData[0][0])) {
				echo 'Tahun file excel tidak sama dengan '.$tahun;
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if (!preg_match('/'.month_name($bulan, 0).'/i', $rowData[0][0]) && !preg_match('/'.month_name($bulan, 1).'/i', $rowData[0][0])) {
				echo 'Bulan file excel tidak sama dengan '.month_name($bulan, 0).' dan '.month_name($bulan, 1);
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if (!preg_match('/'.$tanggal.'/i', $rowData[0][0])) {
				echo 'Tanggal file excel tidak sama dengan '.$tanggal;
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 12 && !preg_match('/stok gudang farmasi/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan Gudang Farmasi';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 91 && !preg_match('/stok depo sentral/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan Sentral';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 93 && !preg_match('/stok depo ird/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan IRD';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 94 && !preg_match('/stok depo poliklinik/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan Poliklinik';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 95 && !preg_match('/stok depo ok ibs/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan OK IBS';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 96 && !preg_match('/stok depo ok ird/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan OK IRD';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 97 && !preg_match('/stok depo ok wing/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan OK Wing';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 98 && (!preg_match('/stok depo cath lab/i', $rowData[0][0]) && !preg_match('/stok depo cast lab/i', $rowData[0][0]))) {
				echo 'Depo file excel tidak sama dengan Cath Lab atau Cast Lab';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 99 && !preg_match('/stok depo kemoterapi/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan Kemoterapi';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			} else if ($depo == 100 && !preg_match('/stok depo wing/i', $rowData[0][0])) {
				echo 'Depo file excel tidak sama dengan Wing';
				echo '<br>';
				echo 'File excel '.$rowData[0][0];
				break;
			}
			$keterangan = $rowData[0][0];
		}			
		
		if ($start) {

			/*if ($depo == 91) {
				$kode_obat = $rowData[0][1];
				$nama_obat = $mysql->real_escape_string($rowData[0][4]);
				$satuan = $rowData[0][5];
				$harga = $rowData[0][9];
				$jumlah = $rowData[0][8];
			} else {
				$kode_obat = $rowData[0][1];
				$nama_obat = $mysql->real_escape_string($rowData[0][4]);
				$satuan = $rowData[0][5];
				$harga = $rowData[0][7];
				$jumlah = $rowData[0][6];
			}*/
			
			$kode_obat = $rowData[0][1];
			$nama_obat = $mysql->real_escape_string($rowData[0][4]);
			$satuan = $rowData[0][5];
			$harga = $rowData[0][7];
			$jumlah = $rowData[0][6];

			echo $sql = "
				INSERT INTO 
					`rsupsanglah`.`{$table}`
				(
					`tgl`,
					`depo_id`,
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
					'{$tahun}-{$bulan}-{$tanggal}',
					'{$depo}',
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

if (isset($_POST['submit']) && $_POST['submit'] == 'View' && !empty($tahun) && !empty($bulan) && !empty($tanggal)) {
	$tgl = "{$tahun}-{$bulan}-{$tanggal}";

	if (!empty($depo)) {
		$where = "AND `depo_id` = '{$depo}'";
	} else {
		$where = "";
	}

	$sql = "
		SELECT
			`kode_obat`,
			`nama_obat`,
			`harga`,
			SUM(`jumlah`) AS `jumlah`,
			SUM(`total`) AS `total`
		FROM
			`rsupsanglah`.`{$table}`
		WHERE
			`tgl` = '{$tgl}'
		{$where}
		GROUP BY
			`kode_obat`
		ORDER BY
			`kode_obat`
	";

	$rs = $mysql->query($sql);
	$jumlah = 0;
	$total = 0;
	$total_hitung = 0;
	echo '<table width="100%" border="1" cellpadding="0" cellspacing="0">';
	echo '<tr>';
	echo '<th>Kode Obat</th>';
	echo '<th>Nama Obat</th>';
	echo '<th>Harga</th>';
	echo '<th>Jumlah</th>';
	echo '<th>Total</th>';
	echo '<th>Total Hitung</th>';
	echo '</tr>';
	while ($row = $rs->fetch_object()) {
		echo '<tr>';
		echo '<td>'.$row->kode_obat.'</td>';
		echo '<td>'.$row->nama_obat.'</td>';
		echo '<td align="right">'.number_format($row->harga,2).'</td>';
		echo '<td align="right">'.number_format($row->jumlah,2).'</td>';
		echo '<td align="right">'.number_format($row->total,2).'</td>';
        if ($row->total != ($row->harga * $row->jumlah)) {
            echo '<td align="right" style="background-color: pink">'.number_format($row->harga * $row->jumlah,2).'</td>';
        } else {
            echo '<td align="right">'.number_format($row->harga * $row->jumlah,2).'</td>';
        }
		echo '</tr>';
		$jumlah = $jumlah + $row->jumlah;
		$total = $total + $row->total;
		$total_hitung = $total_hitung + ($row->harga * $row->jumlah);
	}
	echo '<tr>';
	echo '<td colspan="3">Jumlah</td>';
	echo '<td align="right">'.number_format($jumlah,2).'</td>';
	echo '<td align="right">'.number_format($total,2).'</td>';
    if ($total != $total_hitung) {
        echo '<td align="right" style="background-color: pink">'.number_format($total_hitung,2).'</td>';
    } else {
        echo '<td align="right">'.number_format($total_hitung,2).'</td>';
    }
	echo '</tr>';
	echo '</table>';
}
