<?php
class index {
	private $db;
	function __construct() {
		include 'model/index.php';
		$this->db = new ModelIndex;
		$this->db->test();
	}

	public function error($id = false) {
		
	}

}
?>