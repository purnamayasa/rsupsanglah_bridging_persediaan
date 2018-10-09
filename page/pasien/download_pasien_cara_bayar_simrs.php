<meta http-equiv="refresh" content="1">
<h3>Download Pasien Cara Bayar SIMRS</h3>

<?php

//buat tabel pasien cara bayar
$sql = "
	CREATE TABLE IF NOT EXISTS `rsupsanglah`.`pasien_cara_bayar` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`pasien_id` int(11) DEFAULT NULL,
		`pasien_kunjungan_id` int(11) DEFAULT NULL,
		`cara_bayar_id` int(11) DEFAULT NULL,
		`kelas_rawat_id` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
";

$mysql->query($sql);

if (isset($_SESSION['id'])) {
	$where = "WHERE `pk`.`id` > '{$_SESSION['id']}'";
} else {
	$where = "";
}

$sql = "
	SELECT
		`pk`.`id`,
		`pk`.`pasien_id`,
		`pk`.`tgljam_kunjungan`,
		`p`.`rm`
	FROM
		`rsupsanglah`.`pasien_kunjungan` AS `pk`
	INNER JOIN `rsupsanglah`.`pasien` AS `p` ON `p`.`id` = `pk`.`pasien_id`
	{$where}
	ORDER BY
		`pk`.`id`
	LIMIT 1
";

$rs = $mysql->query($sql);
$row = $rs->fetch_object();
$id = isset($row->id) ? $row->id : '0';
$pasien_id = isset($row->pasien_id) ? $row->pasien_id : '0';
$tgljam_kunjungan = isset($row->tgljam_kunjungan) ? $row->tgljam_kunjungan : '0000-00-00 00:00:00';
$rm = isset($row->rm) ? $row->rm : '0';

$_SESSION['id'] = $id;

echo $sql = "
	SELECT
		`CARA_BAYAR`
	FROM
		`simrs`.`data_kunjungan`
	WHERE
		`NORM` = '{$rm}'
	AND `TGL_REG` = '{$tgljam_kunjungan}'
";

$rs = $mysql_2_13->query($sql);

echo '<hr>';

while ($row = $rs->fetch_object()) {
	//cek apakah pasien cara bayar sudah ada
	$sqlCek = "
		SELECT
			`id`
		FROM
			`rsupsanglah`.`pasien_cara_bayar`
		WHERE
			`pasien_id` = '{$pasien_id}'
		AND `pasien_kunjungan_id` = '{$id}'
		AND `cara_bayar_id` = (
			SELECT
				`id`
			FROM
				`rsupsanglah`.`cara_bayar`
			WHERE
				`nama` = '{$row->CARA_BAYAR}'
		)
	";

	$rsCek = $mysql->query($sqlCek);
	$rowCek = $rsCek->fetch_object();

	//tambah data
	if ($rsCek->num_rows == 0) {
		echo $sql = "
			INSERT INTO 
				`rsupsanglah`.`pasien_cara_bayar`
			(
				`pasien_id`,
				`pasien_kunjungan_id`,
				`cara_bayar_id`
			)
			VALUES
			(
				'{$pasien_id}',
				'{$id}',
				(
					SELECT
						`id`
					FROM
						`rsupsanglah`.`cara_bayar`
					WHERE
						`nama` = '{$row->CARA_BAYAR}'
				)
			)
		";
	}
	
	echo '<hr>';

	$mysql->query($sql);
}