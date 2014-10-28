<?php
class LibBoot {
	function __construct($url) {
		$CGI = (isset($url[0]) and in_array($url[0],array('cgi','body','js')));
		$view = false;
		if($CGI){
			$ck = $url[0];
			if($url[0] == 'js'){
				$url[1] = (!isset($url[1]) or $url[1] == '')?false:$url[1];
				if($url[1] == 'Lang'){
					if(!isset($_SESSION['SiteLang']) and !isset($url[2]))
						$_SESSION['SiteLang'] = 'en';
					else if(isset($_SESSION['SiteLang']) and !isset($url[2]))
						$_SESSION['SiteLang'] = $_SESSION['SiteLang'];
					else if(!isset($_SESSION['SiteLang']) and isset($url[2]))
						$_SESSION['SiteLang'] = $url[2];
				}
				include "control/".$url[0].".php";
				$ck = new $url[0]($url[1]);
				exit;
			}else{
				$url[0] = (isset($url[1]))? $url[1]: 'index';
				$url[1] = (isset($url[2]) and $url[2] != '')? $url[2]: 'index';
			}
			if($ck == 'body'){
				$view = $this->FileCk(SCANDIR('view'), $url[0],false);
				$view = $this->FileCk(SCANDIR('view/'.$view), $url[1]);
			}
		}else{
			$url[0] = (!isset($url[0]) or $url[0] == '')?'index':$url[0];
			$url[1] = (!isset($url[1]) or $url[1] == '')?'index':$url[1];
			$view = $this->FileCk(SCANDIR('view'), $url[0],false);
			$view = $this->FileCk(SCANDIR('view/'.$view), $url[1]);
		}
		
		$control = $this->FileCk(SCANDIR('control'), $url[0]);
		if(isset($_SESSION['PwHand'])){
			$_SESSION['DePwHand'] = $_SESSION['PwHand'];
		}
		if(isset($_SESSION['PwEnCode'])){
			$_SESSION['DePwEnCode'] = $_SESSION['PwEnCode'];
		}
		$data['get'] = $this->InDataCk($_GET);
		$data['post'] = $this->InDataCk($_POST);
		include "control/$control.php";
		$ControlObj = new $control;

		$ControlRet = false;
		if (method_exists($ControlObj, $url[1])) {
			$ControlRet = (count($data['get']) != 0 or count($data['post']) != 0)? $ControlObj->{$url[1]}($data): $ControlObj->{$url[1]}();
		}
		if($view){
			$ControlRet['OnlyBody']=!$CGI;
			include "view/View.php";
			$ViewObj = new View($control.'/'.$view,$ControlRet);
		}
		if(isset($_SESSION['DePwEnCode'])){
			unset($_SESSION['DePwEnCode']);
		}
		if(isset($_SESSION['DePwHand'])){
			unset($_SESSION['DePwHand']);
		}
	}

	private function FileCk($arr, $file_name, $isFile = true) {
		$ret = 'error';
		foreach ($arr as $value) {
			if (substr($value, 0, strrpos($value, ".")) == $file_name and $isFile)
				$ret = $file_name;
			else if ($value == $file_name and !$isFile)
				$ret = $file_name;
		}
		return $ret;
	}

	private function InDataCk($arr) {
		$data = [];
		foreach ($arr as $key => $value) {
			if(isset($_SESSION['DePwHand']) and stristr($value,$_SESSION['DePwHand'])){
				$value = $this->PwDeCode(str_replace($_SESSION['DePwHand'],'',$value));
			}
			$data[$key] = $this->CheckInput($value);
		}
		return $data;
	}

	private function CheckInput($value) {
		if (is_array($value)) {
			foreach ($value as $key2 => $value2)
				$value[$key2] = $this->CheckInput($value2);
		} else {
			$value = str_replace(["&", "'", '"', "<", ">"], ['@&5', '@&1', '@&2', '@&3', '@&4'], $value);
		}
		return $value;
	}
	public function PwDeCode($str){
		$tmp = '';
		$arr = $_SESSION['DePwEnCode'];
		$str = explode('*|*', $str);
		foreach($str as $val){
			foreach($arr as $arr_v){
				if(urldecode($arr_v['val']) == urldecode($val)){
					$tmp .= urldecode($arr_v['id']);
				}
			}
		}
		return $tmp;
	}
}