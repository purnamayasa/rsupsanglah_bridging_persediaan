<?php

class inv_medis
{
	private $_connection;
	
	public function __construct($mysql_connection)
	{
		$this->_connection = $mysql_connection;
	}
	
	public function masuk_sebelumnya($kd_obat, $tgl_awal = null, $tgl_akhir = null)
	{
		if (is_null($tgl_awal) && is_null($tgl_akhir)) {
			$where = "
				AND (PG.TGL_TERIMA < NOW()
				AND PG.TGL_TERIMA <> '0000-00-00 00:00:00')
			";
		} else if (!is_null($tgl_awal) && is_null($tgl_akhir)) {
			$where = "
				AND (PG.TGL_TERIMA < '{$tgl_awal}'
				AND PG.TGL_TERIMA <> '0000-00-00 00:00:00')
			";
		} else {
			$where = "
				AND (PG.TGL_TERIMA BETWEEN '{$tgl_awal}'
				AND '{$tgl_akhir}')
			";
		}
	
		$sql = "
			SELECT
				SUM(PB.jmlterima) AS stok
			FROM
				simrs2012.t_penerimaan_gudang PG
			INNER JOIN simrs2012.t_penerimaan_barang PB ON PB.IDPENERIMAAN = PG.ID
			INNER JOIN simrs2012.m_obat O ON O.kode_obat = PB.kodebarang
			LEFT JOIN simrs2012.t_supp S ON S.id = PG.TERIMA_DARI
			WHERE				
				O.kode_obat = '{$kd_obat}'			
			AND O.flag >= '0'
			AND PB.jmlterima > 0
			AND PB.hargafakturst > 0
			{$where}
		";
		
		//echo $sql.'<br>';
		
		$rs = mysql_query($sql, $this->_connection);
		$row = mysql_fetch_object($rs);
		
		$stok = isset($row->stok) ? $row->stok : '0';
		
		return $stok;
	}
	
	public function masuk($kd_obat, $tgl_awal, $tgl_akhir)
	{
		$sql = "
			SELECT
				SUM(PB.jmlterima) AS stok
			FROM
				simrs2012.t_penerimaan_gudang PG
			INNER JOIN simrs2012.t_penerimaan_barang PB ON PB.IDPENERIMAAN = PG.ID
			INNER JOIN simrs2012.m_obat O ON O.kode_obat = PB.kodebarang
			LEFT JOIN simrs2012.t_supp S ON S.id = PG.TERIMA_DARI
			WHERE				
				O.kode_obat = '{$kd_obat}'			
			AND O.flag >= '0'
			AND PB.jmlterima > 0
			AND PB.hargafakturst > 0
			AND (PG.TGL_TERIMA BETWEEN '{$tgl_awal}' AND '{$tgl_akhir}')
		";
		
		//echo $sql.'<br>';
		
		$rs = mysql_query($sql, $this->_connection);
		$row = mysql_fetch_object($rs);
		
		$stok = isset($row->stok) ? $row->stok : '0';
		
		return $stok;
	}
	
	public function keluar_sebelumnya($kd_obat, $tgl_awal = null, $tgl_akhir = null)
	{
		if (is_null($tgl_awal) && is_null($tgl_akhir)) {
			$where = "
				AND (PA.tglkeluar < NOW()
				AND PA.tglkeluar <> '0000-00-00 00:00:00')
			";
		} else if (!is_null($tgl_awal) && is_null($tgl_akhir)) {
			$where = "
				AND (PA.tglkeluar < '{$tgl_awal}'
				AND PA.tglkeluar <> '0000-00-00 00:00:00')
			";
		} else {
			$where = "
				AND (PA.tglkeluar BETWEEN '{$tgl_awal}'
				AND '{$tgl_akhir}')
			";
		}
	
		$sql = "
			SELECT
				SUM(PA.jmlkeluar) AS stok
			FROM
				simrs2012.t_permintaan_apotek AS PA
			INNER JOIN simrs2012.m_obat AS O ON PA.kodebarang = O.kode_obat
			WHERE
				O.kode_obat = '{$kd_obat}'		
			AND O.flag >= '0'
			AND PA.jmlkeluar > 0
			{$where}
		";
		
		//echo $sql.'<br>';
		
		$rs = mysql_query($sql, $this->_connection);
		$row = mysql_fetch_object($rs);
		
		$stok = isset($row->stok) ? $row->stok : '0';
		
		return $stok;
	}
	
	public function keluar($kd_obat, $tgl_awal, $tgl_akhir)
	{
		$sql = "
			SELECT
				SUM(PA.jmlkeluar) AS stok
			FROM
				simrs2012.t_permintaan_apotek AS PA
			INNER JOIN simrs2012.m_obat AS O ON PA.kodebarang = O.kode_obat
			WHERE
				O.kode_obat = '{$kd_obat}'		
			AND O.flag >= '0'
			AND PA.jmlkeluar > 0
			AND (PA.tglkeluar BETWEEN '{$tgl_awal}' AND '{$tgl_akhir}')
		";
		
		//echo $sql.'<br>';
		
		$rs = mysql_query($sql, $this->_connection);
		$row = mysql_fetch_object($rs);
		
		$stok = isset($row->stok) ? $row->stok : '0';
		
		return $stok;
	}
	
	function stok_akhir_sebelumnya($kd_brg, $tgl) {	
		$stok_masuk  = $this->masuk_sebelumnya($kd_brg, $tgl);
		$stok_keluar = $this->keluar_sebelumnya($kd_brg, $tgl);
		$stok_akhir = $stok_masuk - $stok_keluar;
		
		if ($stok_akhir > 0) {
			$stok = $stok_akhir;
		} else {
			$stok = 0;
		}		
		
		return $stok;
	}
	
	function stok_sekarang($kd_brg, $tgl_awal, $tgl_akhir) {		
		$awal	 = $this->stok_akhir_sebelumnya($kd_brg, $tgl_awal);
		$masuk	 = $this->masuk($kd_brg, $tgl_awal, $tgl_akhir);
		$keluar	 = $this->keluar($kd_brg, $tgl_akhir, $tgl_akhir);
		$akhir	 = ($awal + $masuk) - $keluar;
		
		return $akhir;
	}
}