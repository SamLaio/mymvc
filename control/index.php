<?php
class index {
	private $db;
	public $SiteName;
	function __construct() {
		include 'model/index.php';
		$this->SiteName = '首頁';
		$this->db = new ModelIndex;
	}
}