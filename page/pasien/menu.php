<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

?>

<?php if ($ajax != 1) { ?>
<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
	<option value="<?php echo URL.'index.php?url=pasien'; ?>">-PILIH-</option>
	<option value="<?php echo URL.'index.php?url=pasien/download_cara_bayar_simrs'; ?>" <?php echo (isset($url_params[0]) && $url_params[0] == 'download_cara_bayar_simrs') ? 'selected' : ''; ?>>Download Cara Bayar SIMRS</option>
	<option value="<?php echo URL.'index.php?url=pasien/download_pasien_simrs'; ?>" <?php echo (isset($url_params[0]) && $url_params[0] == 'download_pasien_simrs') ? 'selected' : ''; ?>>Download Pasien SIMRS</option>
	<option value="<?php echo URL.'index.php?url=pasien/download_pasien_kunjungan_simrs'; ?>" <?php echo (isset($url_params[0]) && $url_params[0] == 'download_pasien_kunjungan_simrs') ? 'selected' : ''; ?>>Download Pasien Kunjungan SIMRS</option>
	<option value="<?php echo URL.'index.php?url=pasien/download_pasien_cara_bayar_simrs'; ?>" <?php echo (isset($url_params[0]) && $url_params[0] == 'download_pasien_cara_bayar_simrs') ? 'selected' : ''; ?>>Download Pasien Cara Bayar SIMRS</option>
</select>
<hr>
<?php } ?>