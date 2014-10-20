<?php
if(!isset($_SESSION))
	session_start();
include_once 'lib/LibBoot.php';
include_once 'lib/LibDataBase.php';
if(!file_exists('lib/Config.php') and !strpos($_SERVER['REQUEST_URI'],'install')){
	header('Location: install');
}else if(file_exists('lib/Config.php')){
	include 'lib/config.php';
	if(isset($DbType))
		define('DbType', $DbType);
	if(isset($DbHost))
		define('DbHost', $DbHost);
	if(isset($DbUser))
		define('DbUser', $DbUser);
	if(isset($DbPw))
		define('DbPw', $DbPw);
	if(isset($DbName))
		define('DbName', $DbName);
	include 'model/load.php';
	$Load = new Load;
	if(strpos($_SERVER['REQUEST_URI'],'install')){
		header('Location: index');
	}
}
$baseUrl = explode('/',$_SERVER['PHP_SELF']);
$ck = false;
$url = array();
foreach($baseUrl as $baseK => $baseV){
	if($ck)
		$url[] = $baseV;
	if($baseV == 'load.php')
		$ck = true;
}
$boot = new LibBoot($url);
