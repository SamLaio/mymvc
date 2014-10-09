<?php
class Load extends LibDataBase {
	function __construct() {
		parent::__construct();
		if(!isset($_SESSION['SiteName']) or !isset($_SESSION['SiteUrl'])){
			$re = $this->Assoc('site','*');
			$_SESSION['SiteName'] = $re[0]['name'];
			$_SESSION['SiteUrl'] = $re[0]['url'];
		}
	}
}