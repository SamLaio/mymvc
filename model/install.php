<?php
class ModeInstall extends LibDataBase {
	private $to_link;
	public function St1() {
		include_once 'lib/Config.php';
		$this->dbtype = $DbType;
		if ($DbType == 'mysql') {
			$this->dbhost = $DbHost;
			$this->dbuser = $DbUser;
			$this->dbpass = $DbPw;
			$this->dbname = $DbName;
		}
		if ($DbType == 'sqlite') {
			$this->dbname = $DbName;
		}
		$this->to_link = $this->link();
	}
	public function St2(){
		if($this->dbtype == 'mysql'){
			//CREATE DATABASE `test` /*!40100 COLLATE 'utf8_unicode_ci' */
			$this->Query("create database `$this->dbname` /*!40100 COLLATE 'utf8_unicode_ci';");
		}
	}
}