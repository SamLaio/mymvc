<?php
class install {
	private $installObj;
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
		fwrite($fp,$str);
		fclose($fp);
		$arr = $arr['post'];
		include 'model/install.php';
		$this->installObj = new ModeInstall;
		$this->installObj->St1();
		$this->St2();
	}
	private function St2(){
		$this->installObj->St2();
	}
}

