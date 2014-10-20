<?php
	$strLang=array(
		'InstallInfo'=>array('type'=>'def','val'=>'Install Info'),
		'AdminName'=>array('type'=>'def','val'=>'Administrator Name:'),
		'AdminPw'=>array('type'=>'def','val'=>'Administrator Password:'),
		'SiteName'=>array('type'=>'def','val'=>'Site Name:'),
		'SiteUrl'=>array('type'=>'def','val'=>'Site Url:'),
		'SiteLang'=>array('type'=>'def','val'=>'Site Language:'),
		'DbType'=>array('type'=>'def','val'=>'DataBase Type:'),
		'DbName'=>array('type'=>'def','val'=>'DataBase Name:'),
		'DbHost'=>array('type'=>'def','val'=>'DataBase Link:'),
		'DbAdame'=>array('type'=>'def','val'=>'DataBase Administrator Name:'),
		'DbAdPw'=>array('type'=>'def','val'=>'DataBase Administrator Password:'),
		
		'account'=>array('type'=>'def','val'=>'Account:'),
		'password'=>array('type'=>'def','val'=>'Password:'),
		'captcha'=>array('type'=>'def','val'=>'Captcha:'),
		'submit'=>array('type'=>'input','val'=>'Submit'),
		
		'goIndex'=>array('type'=>'def','val'=>'Index'),
		'login'=>array('type'=>'def','val'=>'Login'),
		'logout'=>array('type'=>'def','val'=>'Logout')
	);
?>
$(window).ready(function(){
<?php
	foreach($strLang as $key => $value){
		if($value['type'] == 'def')
			$tmp = 'html';
		if($value['type'] == 'input')
			$tmp = 'val';
		echo "\tif($('.Lang_$key').length > 0) $('.Lang_$key').$tmp('".$value['val']."');\n";
	}
?>
});