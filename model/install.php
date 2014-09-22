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
	}
	public function St2(){
		if($this->dbtype == 'mysql'){
			//CREATE DATABASE `test` /*!40100 COLLATE 'utf8_unicode_ci' */
			$this->Query("create database `$this->dbname` /*!40100 COLLATE 'utf8_unicode_ci';");
		}elseif($this->dbtype=='sqlite'){
			//echo 11;
			$this->Query('CREATE TABLE user ([seq] INTEGER  PRIMARY KEY NOT NULL,[account] TEXT  NOT NULL,[pswd] TEXT  NOT NULL,[name] TEXT  NOT NULL,[status] INTEGER  NOT NULL);');
			$this->Query('CREATE UNIQUE INDEX [IDX_USER_SEQ] ON [user]([seq]  DESC);');
		}
	}
}