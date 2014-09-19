<?php
class install {

	function __construct() {
		
	}
	function St1($arr){
			/*
				$DbType = 'mysql';
				$DbHost = '192.168.247.33';
				$DbUser = 'root';
				$DbName = 'mymvc';
				$DbPw = 'phitech';
			 */
		if(isset($arr['post']['DbType']) and $arr['post']['DbType'] != '')
			$str = '$'."DbType = '".$arr['post']['DbType']."';\n";
		if(isset($arr['post']['DbHost']) and $arr['post']['DbHost'] != '')
			$str .= '$'."DbHost = '".$arr['post']['DbHost']."';\n";
		if(isset($arr['post']['DbUser']) and $arr['post']['DbUser'] != '')
			$str .= '$'."DbUser = '".$arr['post']['DbUser']."';\n";
		if(isset($arr['post']['DbName']) and $arr['post']['DbName'] != ''){
			if($arr['post']['DbType'] != 'sqlite')
				$str .= '$'."DbName = '".$arr['post']['DbName']."';\n";
			else
				$str .= '$'."DbName = 'model/".$arr['post']['DbName']."';\n";
		}
		
			
		$str = "<?php\n".$str."?>";
		$fp = fopen('lib/Config.php','w+');
		//$file = fopen("test.xml","a+"); //開啟檔案
		fwrite($fp,$str);
		fclose($fp);
		$this->St2();
	}
	private function St2(){
		include 'model/install.php';
		$installObj = new ModeInstall;
		$installObj->St1();
	}
}

