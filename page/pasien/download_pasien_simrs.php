<meta http-equiv="refresh" content="1">
<h3>Download Pasien SIMRS</h3>

<?php

//buat tabel pasien
$sql = "
	CREATE TABLE IF NOT EXISTS `rsupsanglah`.`pasien` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`rm` varchar(8) DEFAULT NULL,
		`nama` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
";

$mysql->query($sql);

$sql = "
	SELECT
		`rm`
	FROM
		`rsupsanglah`.`pasien`
	ORDER BY
		`rm` DESC
	LIMIT 1
";

$rs = $mysql->query($sql);
$row = $rs->fetch_object();
$rm = isset($row->rm) ? $row->rm : '0';

$sql = "
	SELECT
		`NORM`,
		`NAMA_PASIEN`
	FROM
		`simrs`.`data_demografi`
	WHERE
		`NORM` > '{$rm}'
	LIMIT 1
";

$rs = $mysql_2_13->query($sql);

while ($row = $rs->fetch_object()) {
	//cek apakah rm sudah ada
	$sqlCek = "
		SELECT
			`id`
		FROM
			`rsupsanglah`.`pasien`
		WHERE
			`rm` = '{$row->NORM}'
	";

	$rsCek = $mysql->query($sqlCek);

	//tambah data
	if ($rsCek->num_rows == 0) {
		echo $sql = "
			INSERT INTO 
				`rsupsanglah`.`pasien`
			(
				`rm`,
				`nama`
			)
			VALUES
			(
				'{$row->NORM}',
				'{$row->NAMA_PASIEN}'
			)
		";
	}
	
	echo '<hr>';

	$mysql->query($sql);
}