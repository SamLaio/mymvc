<?php
class ModelLogin extends LibDataBase {
	function __construct() {
		parent::__construct();
		if(isset($_SESSION['UserId']))
			header('Location: index');
	}
	public function UserCk($arr){
		$account = $this->Assoc('user','*',"account = '".$arr['post']['account']."' and pswd = '".md5($arr['post']['pswd'])."'");
		if($this->count == 0)
			return false;
		else
			return $account[0];
	}
}