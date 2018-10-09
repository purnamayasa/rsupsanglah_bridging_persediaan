<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

?>
<?php if ($ajax != 1) { ?>
<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
	<option value="<?php echo URL.'index.php?url=persediaan'; ?>">-PILIH-</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/copy_db'; ?>">Copy Database Persediaan BLU to localhost</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-01-01 00:00:00&tglakhir=2017-01-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-01</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-02-01 00:00:00&tglakhir=2017-02-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-02</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-03-01 00:00:00&tglakhir=2017-03-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-03</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-04-01 00:00:00&tglakhir=2017-04-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-04</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-05-01 00:00:00&tglakhir=2017-05-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-05</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-06-01 00:00:00&tglakhir=2017-06-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-06</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-07-01 00:00:00&tglakhir=2017-07-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-07</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-08-01 00:00:00&tglakhir=2017-08-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-08</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-09-01 00:00:00&tglakhir=2017-09-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-09</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-10-01 00:00:00&tglakhir=2017-10-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-10</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-11-01 00:00:00&tglakhir=2017-11-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-11</option>
	<option value="<?php echo URL.'index.php?url=persediaan/blu/create_txt&ajax=1&tglawal=2017-12-01 00:00:00&tglakhir=2017-12-31 23:59:59'; ?>">Create TXT Persediaan BLU 2017-12</option>
	
	<option value="<?php echo URL.'index.php?url=persediaan'; ?>"></option>

	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/penerimaan_faktur'; ?>">Import Excel Farmasi Penerimaan Faktur</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/penerimaan_tt'; ?>">Import Excel Farmasi Penerimaan TT</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/stokopname'; ?>">Import Excel Farmasi Stokopname</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/dbsedia_blu'; ?>">Import Excel Farmasi Persediaan BLU</option>

    <option value="<?php echo URL.'index.php?url=persediaan'; ?>"></option>
    
    <option value="<?php echo URL.'index.php?url=persediaan/non_medis/stokopname'; ?>">Import Excel Non Medis Stokopname</option>
    <option value="<?php echo URL.'index.php?url=persediaan/non_medis/dbsedia_blu'; ?>">Import Excel Non Medis Persediaan BLU</option>  
	
	<option value="<?php echo URL.'index.php?url=persediaan'; ?>"></option>

	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2017-10-01 00:00:00&tglakhir=2017-10-31 23:59:59'; ?>">Download TXT Gizi 2017-10</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2017-11-01 00:00:00&tglakhir=2017-11-30 23:59:59'; ?>">Download TXT Gizi 2017-11</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2017-12-01 00:00:00&tglakhir=2017-12-31 23:59:59'; ?>">Download TXT Gizi 2017-12</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-01-01 00:00:00&tglakhir=2018-01-31 23:59:59'; ?>">Download TXT Gizi 2018-01</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-02-01 00:00:00&tglakhir=2018-02-31 23:59:59'; ?>">Download TXT Gizi 2018-02</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-03-01 00:00:00&tglakhir=2018-03-31 23:59:59'; ?>">Download TXT Gizi 2018-03</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-04-01 00:00:00&tglakhir=2018-04-31 23:59:59'; ?>">Download TXT Gizi 2018-04</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-05-01 00:00:00&tglakhir=2018-05-31 23:59:59'; ?>">Download TXT Gizi 2018-05</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-06-01 00:00:00&tglakhir=2018-06-31 23:59:59'; ?>">Download TXT Gizi 2018-06</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-07-01 00:00:00&tglakhir=2018-07-31 23:59:59'; ?>">Download TXT Gizi 2018-07</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-08-01 00:00:00&tglakhir=2018-08-31 23:59:59'; ?>">Download TXT Gizi 2018-08</option>
	<option value="<?php echo URL.'index.php?url=persediaan/gizi/txt&ajax=1&tglawal=2018-09-01 00:00:00&tglakhir=2018-09-31 23:59:59'; ?>">Download TXT Gizi 2018-09</option>
	<option value="<?php echo URL.'index.php?url=persediaan'; ?>"></option>

	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2017-10-01 00:00:00&tglakhir=2017-10-31 23:59:59'; ?>">Download TXT Non Medis 2017-10</option>
	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2017-11-01 00:00:00&tglakhir=2017-11-30 23:59:59'; ?>">Download TXT Non Medis 2017-11</option>
	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2017-12-01 00:00:00&tglakhir=2017-12-31 23:59:59'; ?>">Download TXT Non Medis 2017-12</option>
    <option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-01-01 00:00:00&tglakhir=2018-01-31 23:59:59'; ?>">Download TXT Non Medis 2018-01</option>
    <option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-02-01 00:00:00&tglakhir=2018-02-31 23:59:59'; ?>">Download TXT Non Medis 2018-02</option>
    <option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-03-01 00:00:00&tglakhir=2018-03-31 23:59:59'; ?>">Download TXT Non Medis 2018-03</option>
	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-04-01 00:00:00&tglakhir=2018-04-31 23:59:59'; ?>">Download TXT Non Medis 2018-04</option>
	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-05-01 00:00:00&tglakhir=2018-05-31 23:59:59'; ?>">Download TXT Non Medis 2018-05</option>
	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-06-01 00:00:00&tglakhir=2018-06-31 23:59:59'; ?>">Download TXT Non Medis 2018-06</option>
	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-07-01 00:00:00&tglakhir=2018-07-31 23:59:59'; ?>">Download TXT Non Medis 2018-07</option>
	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-08-01 00:00:00&tglakhir=2018-08-31 23:59:59'; ?>">Download TXT Non Medis 2018-08</option>
	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt&ajax=1&tglawal=2018-09-01 00:00:00&tglakhir=2018-09-31 23:59:59'; ?>">Download TXT Non Medis 2018-09</option>
    <option value="<?php echo URL.'index.php?url=persediaan'; ?>"></option>

    <option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2017-10-01 00:00:00&tglakhir=2017-10-31 23:59:59'; ?>">Download TXT Farmasi 2017-10</option>
    <option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2017-11-01 00:00:00&tglakhir=2017-11-30 23:59:59'; ?>">Download TXT Farmasi 2017-11</option>
    <option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2017-12-01 00:00:00&tglakhir=2017-12-31 23:59:59'; ?>">Download TXT Farmasi 2017-12</option>
    <option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-01-01 00:00:00&tglakhir=2018-01-31 23:59:59'; ?>">Download TXT Farmasi 2018-01</option>
    <option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-02-01 00:00:00&tglakhir=2018-02-31 23:59:59'; ?>">Download TXT Farmasi 2018-02</option>
    <option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-03-01 00:00:00&tglakhir=2018-03-31 23:59:59'; ?>">Download TXT Farmasi 2018-03</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-04-01 00:00:00&tglakhir=2018-04-31 23:59:59'; ?>">Download TXT Farmasi 2018-04</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-05-01 00:00:00&tglakhir=2018-05-31 23:59:59'; ?>">Download TXT Farmasi 2018-05</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-06-01 00:00:00&tglakhir=2018-06-31 23:59:59'; ?>">Download TXT Farmasi 2018-06</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-07-01 00:00:00&tglakhir=2018-07-31 23:59:59'; ?>">Download TXT Farmasi 2018-07</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-08-01 00:00:00&tglakhir=2018-08-31 23:59:59'; ?>">Download TXT Farmasi 2018-08</option>
	<option value="<?php echo URL.'index.php?url=persediaan/farmasi/txt&ajax=1&tglawal=2018-09-01 00:00:00&tglakhir=2018-09-31 23:59:59'; ?>">Download TXT Farmasi 2018-09</option>
	<option value="<?php echo URL.'index.php?url=persediaan'; ?>"></option>

	<option value="<?php echo URL.'index.php?url=persediaan/non_medis/txt_stokopname&ajax=1&tgl=2018-06-30 23:59:59'; ?>">Download TXT Stokopname Non Medis 2018-06</option>
</select>
<hr>
<?php } ?>
