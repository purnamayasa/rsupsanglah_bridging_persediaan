<meta http-equiv="refresh" content="1">
<h3>Download Pasien Kunjungan SIMRS</h3>

<?php

//buat tabel pasien
$sql = "
	CREATE TABLE IF NOT EXISTS `rsupsanglah`.`pasien_kunjungan` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`pasien_id` int(11) DEFAULT NULL,
		`tgljam_kunjungan` datetime DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
";

$mysql->query($sql);

if (isset($_SESSION['rm'])) {
	$where = "WHERE rm > '{$_SESSION['rm']}'";
} else {
	$where = "";
}

$sql = "
	SELECT
		`id`,
		`rm`
	FROM
		`rsupsanglah`.`pasien`
	{$where}
	ORDER BY
		`rm`
	LIMIT 1
";

$rs = $mysql->query($sql);
$row = $rs->fetch_object();
$id = isset($row->id) ? $row->id : '0';
$rm = isset($row->rm) ? $row->rm : '0';

$_SESSION['rm'] = $rm;

echo $sql = "
	SELECT
		`NORM`,
		`TGL_REG`
	FROM
		`simrs`.`data_kunjungan`
	WHERE
		`NORM` = '{$rm}'
";

$rs = $mysql_2_13->query($sql);

echo '<hr>';

while ($row = $rs->fetch_object()) {
	//cek apakah rm sudah ada
	$sqlCek = "
		SELECT
			`id`
		FROM
			`rsupsanglah`.`pasien_kunjungan`
		WHERE
			`pasien_id` = '{$id}'
		AND `tgljam_kunjungan` = '{$row->TGL_REG}'
	";

	$rsCek = $mysql->query($sqlCek);
	$rowCek = $rsCek->fetch_object();

	//tambah data
	if ($rsCek->num_rows == 0) {
		echo $sql = "
			INSERT INTO 
				`rsupsanglah`.`pasien_kunjungan`
			(
				`pasien_id`,
				`tgljam_kunjungan`
			)
			VALUES
			(
				'{$id}',
				'{$row->TGL_REG}'
			)
		";
	}
	
	echo '<hr>';

	$mysql->query($sql);
}