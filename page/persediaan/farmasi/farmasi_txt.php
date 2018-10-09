<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

$tglAwal    = isset($_GET['tglawal']) ? $_GET['tglawal'] : '';
$tglAkhir   = isset($_GET['tglakhir']) ? $_GET['tglakhir'] : '';

if (empty($tglAwal)) {
	die;
}

if (empty($tglAkhir)) {
	die;
}

$tglAwalx = str_replace(':','',$tglAwal);
$tglAwalx = str_replace('-','',$tglAwalx);
$tglAwalx = str_replace(' ','',$tglAwalx);
$tglAkhirx = str_replace(':','',$tglAkhir);
$tglAkhirx = str_replace('-','',$tglAkhirx);
$tglAkhirx = str_replace(' ','',$tglAkhirx);

$kdLokasi = '024042200415661002KD';
$kdLokasiInduk = '024042200415661000KD';
$kdPerk = '115199';
$kdKbrg = '1010401002';
$panjangKode = '20';

$sql = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(`tgljam`,'%Y') thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(`tgljam`,'%Y'),LPAD(DATE_FORMAT(`tgljam`,'%m%d'),5,0),'M') AS nodok,
		NOW() AS tgldok,
		`tgljam` AS tglbuku,
		CONCAT('{$kdKbrg}', LPAD(`kode_obat`, '{$panjangKode}', '0')) AS kd_brg,
		`jumlah` AS kuantitas,
		CONCAT('PEMBELIAN',DATE_FORMAT(`tgljam`,'%Y%m')) AS keterangan,
		`nama_supplier` AS asal,
		`no_faktur` AS nobukti,
		'M02' AS jns_trn,
		`harga` AS rph_sat,
		'' rph_aset,
		'' flagkirim,
		'' akun,
		LPAD(`kode_obat`, '{$panjangKode}', '0') AS kd_brg2,
		CONCAT('_',`nama_obat`) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		satuan
	FROM
		(
		SELECT
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
			`keterangan`,
			'faktur' as jenis
		FROM
			`rsupsanglah`.`farmasi_penerimaan_faktur_excel`
		UNION
		SELECT
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
			`keterangan`,
			'tt' as jenis
		FROM
			`rsupsanglah`.`farmasi_penerimaan_tt_excel`
	) AS penerimaan
	WHERE
		`tgljam` BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
	ORDER BY
		`tgljam`
";

$rs = $mysql->query($sql);

$posts = array();

while ($row = $rs->fetch_object()) {
	$posts[] = array(
		$row->kd_lokasi,
		$row->ur_brg2,
		$row->thn_ang,
		$row->nodok,
		date('d-m-Y H:i:s',strtotime($row->tgldok)),
		date('d-m-Y H:i:s',strtotime($row->tglbuku)),
		$row->kd_kbrg2,
		$row->kd_brg2,
		number_format($row->kuantitas, 2, '.', ''),
		$row->satuan,
		$row->asal,
		$row->nobukti,
		$row->jns_trn,
		number_format($row->rph_sat, 2, '.', ''),
		number_format($row->kuantitas * $row->rph_sat, 2, '.', ''),
		$row->flagkirim,
	);
}

$sqlObat = "
	SELECT
		'{$kdLokasi}' AS kd_lokasi,
		'' AS kd_lokasi2,
		DATE_FORMAT(`tgl`,'%Y') thn_ang,
		CONCAT('{$kdLokasi}',DATE_FORMAT(`tgl`,'%Y'),LPAD(DATE_FORMAT(`tgl`,'%m%d'),5,0),'K') AS nodok,
		NOW() AS tgldok,
		CONCAT(`tgl`, '23:59:59') AS tglbuku,
		CONCAT('{$kdKbrg}', LPAD(`kode_obat`, '{$panjangKode}', '0')) AS kd_brg,
		`jumlah` AS kuantitas,
		CONCAT('PENGELUARAN',DATE_FORMAT(`tgl`,'%Y%m')) AS keterangan,
		CONCAT('PENGELUARAN',DATE_FORMAT(`tgl`,'%Y%m')) AS asal,
		CONCAT('PENGELUARAN',DATE_FORMAT(`tgl`,'%Y%m')) AS nobukti,
		'K01' AS jns_trn,
		`harga` AS rph_sat,
		'' rph_aset,
		'' flagkirim,
		'' akun,
		LPAD(`kode_obat`, '{$panjangKode}', '0') AS kd_brg2,
		CONCAT('_',`nama_obat`) AS ur_brg2,
		'{$kdKbrg}' AS kd_kbrg2,
		satuan,
		`kode_obat`
	FROM
		`rsupsanglah`.`farmasi_stokopname_excel`
	WHERE
		`tgl`  BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
	GROUP BY
		`kode_obat`
";

$rsObat = $mysql->query($sqlObat);

while ($rowObat = $rsObat->fetch_object()) {
	$sqlAwal = "
		SELECT 
		    SUM(`jumlah`) AS `jumlah`
		FROM 
			`rsupsanglah`.`farmasi_penerimaan_dbsedia_blu_excel`
		WHERE
			`kd_brg2` = '{$rowObat->kode_obat}'
		AND `kd_lokasi` = '{$kdLokasi}'
		AND `kd_sskel` = '{$kdKbrg}'
		AND `tgl` BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
		AND `kd_sskel` NOT IN ('1010401999', '1010301005')
		GROUP BY 
			`kd_brg2`
	";

	$rsAwal = $mysql->query($sqlAwal);
	
	if ($rsAwal->num_rows == 0) {
		$awal = 0;
	} else {
		$awal = $rsAwal->fetch_object()->jumlah;
	}

	$sqlMasuk = "
		SELECT
			SUM(`jumlah`) AS `jumlah`
		FROM
			(
			SELECT
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
				`keterangan`,
                'faktur' as jenis
			FROM
				`rsupsanglah`.`farmasi_penerimaan_faktur_excel`
			UNION
			SELECT
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
				`keterangan`,
                'tt' as jenis
			FROM
				`rsupsanglah`.`farmasi_penerimaan_tt_excel`
		) AS penerimaan
		WHERE
			`kode_obat` = '{$rowObat->kode_obat}'
		AND	`tgljam` BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
        GROUP BY
            `kode_obat`
	";

	$rsMasuk = $mysql->query($sqlMasuk);
	
	if ($rsMasuk->num_rows == 0) {
		$masuk = 0;
	} else {
		$masuk = $rsMasuk->fetch_object()->jumlah;
	}

	$sqlAkhir = "
		SELECT
			SUM(`jumlah`) AS `jumlah`
		FROM
			`rsupsanglah`.`farmasi_stokopname_excel`
		WHERE
			`kode_obat` = '{$rowObat->kode_obat}'
		AND	`tgl` BETWEEN '{$tglAwal}' AND '{$tglAkhir}'
        GROUP BY
            `kode_obat`
	";

	$rsAkhir = $mysql->query($sqlAkhir);

	if ($rsAkhir->num_rows == 0) {
		$akhir = 0;
	} else {
		$akhir = $rsAkhir->fetch_object()->jumlah;
	}

	$keluar = ($awal + $masuk) - $akhir;

	if ($keluar == 0) {
		continue;
	}

	$posts[] = array(
		$rowObat->kd_lokasi,
		$rowObat->ur_brg2,
		$rowObat->thn_ang,
		$rowObat->nodok,
		date('d-m-Y H:i:s',strtotime($rowObat->tgldok)),
		date('d-m-Y H:i:s',strtotime($rowObat->tglbuku)),
		$rowObat->kd_kbrg2,
		$rowObat->kd_brg2,
		number_format($keluar, 2, '.', ''),
		$rowObat->satuan,
		$rowObat->asal,
		$rowObat->nobukti,
		$rowObat->jns_trn,
		number_format($rowObat->rph_sat, 2, '.', ''),
		number_format($keluar * $rowObat->rph_sat, 2, '.', ''),
		$rowObat->flagkirim,
	);
}

header('Content-type: text/plain');
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=farmasi_{$tglAwalx}_{$tglAkhirx}_".date('YmdHis').".txt");		

foreach($posts as $index => $post) {		
	if(is_array($post)) {
		foreach($post as $key => $value) {
			if ($key == 0) { //1
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 1) { //2
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 2) { //3
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 3) { //4
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 4) { //5
				$post[$key] = $value;
			} else if ($key == 5) { //6
				$post[$key] = $value;
			} else if ($key == 6) { //7
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 7) { //8
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 8) { //9
				$post[$key] = $value;
			} else if ($key == 9) { //10
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 10) { //11
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 11) { //12
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 12) { //13
				$post[$key] = '|'.trim($value).'|';
			} else if ($key == 13) { //14
				$post[$key] = $value;
			} else if ($key == 14) { //15
				$post[$key] = $value;
			} else if ($key == 15) { //16
				$post[$key] = '|'.trim($value).'|';
			} else {
				$post[$key] = $value;
			}
		}
		
		echo implode(',', $post);
	}
	echo "\n";
}
