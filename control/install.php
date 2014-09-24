<?php
class install {
	private $installObj;
	function __construct() {
		if(file_exists('lib/Config.php')){
			include 'lib/Config.php';
			if(isset($DbType)){
				echo '<script>history.back(1);</script>';
				exit;
			}
		}
	}
	function St1($arr){
		$arr = $arr['post'];
		/*
		if(isset($arr['AdName']) and $arr['AdName'] != '')
			$str = '$'."AdName = '".$arr['AdName']."';\n";
		
		if(isset($arr['AdPw']) and $arr['AdPw'] != '')
			$str = '$'."AdPw = '".$arr['AdPw']."';\n";
		*/
		//all
		if(isset($arr['DbType']) and $arr['DbType'] != '')
			$str = '$'."DbType = '".$arr['DbType']."';\n";
		if(isset($arr['DbName']) and $arr['DbName'] != ''){
			if($arr['DbType'] != 'sqlite')
				$str .= '$'."DbName = '".$arr['DbName']."';\n";
			else
				$str .= '$'."DbName = 'model/".$arr['DbName']."';\n";
		}
		//all
		//mysql
		if(isset($arr['DbHost']) and $arr['DbHost'] != '')
			$str .= '$'."DbHost = '".$arr['DbHost']."';\n";
		if(isset($arr['DbAdName']) and $arr['DbAdName'] != '')
			$str .= '$'."DbUser = '".$arr['DbAdName']."';\n";
		if(isset($arr['DbAdPw']) and $arr['DbAdPw'] != '')
			$str .= '$'."DbPw = '".$arr['DbAdPw']."';\n";
		//mysql
		$str = "<?php\n".$str."?>";
		$fp = fopen('lib/Config.php','w+');
		fwrite($fp,$str);
		fclose($fp);
		include 'model/install.php';
		$this->installObj = new ModeInstall;
		$this->installObj->St1();
		$this->installObj->St2(array('AdName'=>$arr['AdName'],'AdPw'=>$arr['AdPw']));
	}
}

