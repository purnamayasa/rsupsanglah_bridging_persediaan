<meta http-equiv="refresh" content="1">
<h3>Download Cara Bayar SIMRS</h3>

<?php

//buat tabel pasien
$sql = "
	CREATE TABLE IF NOT EXISTS `rsupsanglah`.`cara_bayar` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`nama` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
";

$mysql->query($sql);

$sql = "
	SELECT
		`ClientName`
	FROM
		`simrs`.`ClientMaster`
	GROUP BY
		`ClientName`
	ORDER BY
		ClientName
";

$rs = $mysql_2_13->query($sql);

while ($row = $rs->fetch_object()) {
	//cek apakah cara bayar sudah ada
	$sqlCek = "
		SELECT
			`id`
		FROM
			`rsupsanglah`.`cara_bayar`
		WHERE
			`nama` = '{$row->ClientName}'
	";

	$rsCek = $mysql->query($sqlCek);
	$rowCek = $rsCek->fetch_object();

	//tambah data
	if ($rsCek->num_rows == 0) {
		echo $sql = "
			INSERT INTO 
				`rsupsanglah`.`cara_bayar`
			(
				`nama`
			)
			VALUES
			(
				'{$row->ClientName}'
			)
		";
	}
	
	echo '<hr>';

	$mysql->query($sql);
}