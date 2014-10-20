<?php
class captcha {
	private $LibCaptcha;
	function __construct() {
		include 'lib/LibCaptcha.php';
		$this->LibCaptcha = new LibCaptcha;
	}
	public function ImgPut(){
		$_SESSION['CaptchaPw'] = rand(50,500000);
		$this->LibCaptcha->creat_images($_SESSION['CaptchaPw']);
	}
	public function ImgCheck($key){
		$key = $key['post']['captcha'];
		if(isset($_SESSION['CaptchaPw'])){
			if($this->LibCaptcha->check_images($_SESSION['CaptchaPw'],$key))
				echo 1;
			else
				echo 0;
		}else
			echo 0;
	}
}
?>