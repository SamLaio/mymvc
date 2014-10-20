<?php
	$strLang=array(
		'InstallInfo'=>array('type'=>'def','val'=>'安裝設定'),
		'AdminName'=>array('type'=>'def','val'=>'管理員帳號:'),
		'AdminPw'=>array('type'=>'def','val'=>'管理員密碼:'),
		'SiteName'=>array('type'=>'def','val'=>'網站名稱:'),
		'SiteUrl'=>array('type'=>'def','val'=>'網站網址:'),
		'SiteLang'=>array('type'=>'def','val'=>'網站語言:'),
		'DbType'=>array('type'=>'def','val'=>'資料庫類型:'),
		'DbName'=>array('type'=>'def','val'=>'資料庫名稱:'),
		'DbHost'=>array('type'=>'def','val'=>'資料庫連線:'),
		'DbAdame'=>array('type'=>'def','val'=>'資料庫管理員帳號:'),
		'DbAdPw'=>array('type'=>'def','val'=>'資料庫管理員密碼:'),
		
		'account'=>array('type'=>'def','val'=>'帳號:'),
		'password'=>array('type'=>'def','val'=>'密碼:'),
		'captcha'=>array('type'=>'def','val'=>'驗證碼:'),
		'submit'=>array('type'=>'input','val'=>'送出'),
		
		'goIndex'=>array('type'=>'def','val'=>'首頁'),
		'login'=>array('type'=>'def','val'=>'登入'),
		'logout'=>array('type'=>'def','val'=>'登出')
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