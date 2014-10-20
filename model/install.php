<?php
class InstallModel extends LibDataBase {
	function __construct() {
		parent::__construct();
	}
	public function St1($arr){
		if($this->dbtype == 'mysql'){
			$this->Query("CREATE DATABASE IF NOT EXISTS `$this->dbname` /*!40100 DEFAULT CHARACTER SET latin1 */;");
			$this->Query("USE `$this->dbname`;");
			$this->Query("CREATE TABLE `user` (`seq` int(11) NOT NULL AUTO_INCREMENT,  `account` text NOT NULL,  `pswd` text NOT NULL,  `name` text,  `status` int(11) NOT NULL DEFAULT '1',  PRIMARY KEY (`seq`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
			$this->Query("CREATE TABLE `site` (	`account` TEXT NOT NULL, `url` TEXT NOT NULL, `lang` TEXT NOT NULL)COLLATE='latin1_swedish_ci' ENGINE=MyISAM;");
		}elseif($this->dbtype=='sqlite'){
			$this->Query("CREATE TABLE [user] ([seq] INTEGER  NOT NULL PRIMARY KEY,[account] TEXT  NOT NULL,[pswd] TEXT  NOT NULL,[name] TEXT  NOT NULL,[status] INTEGER DEFAULT '1' NOT NULL);");
			$this->Query('CREATE UNIQUE INDEX [IDX_USER_] ON [user]([seq]  DESC);');
			$this->Query('CREATE TABLE [site] ([name] text  NOT NULL,[url] text  NULL,[lang] text  NULL)');
		}
		$this->Query($this->In('user', array('account','pswd','name'), array("'".$arr['AdName']."'","'".md5($arr['AdPw'])."'","'Admin'")));
		//echo $this->In('site', array('name','url'), array("'".$arr['SiteName']."'","'".$arr['SiteUrl']."'","'Admin'"));
		$this->Query($this->In('site', array('name','url','lang'), array("'".$arr['SiteName']."'","'".$arr['SiteUrl']."'","'".$arr['SiteLang']."'")));
		return true;
	}
}