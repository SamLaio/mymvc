<?php
class index {
	private $db;
	function __construct() {
		include 'model/index.php';
		$this->db = new ModelIndex;
	}
}