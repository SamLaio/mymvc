<?php
	class LibCaptcha{
		function __construct() {
			/*if(!isset($_SESSION))
				session_start();*/
			if(!isset($_SESSION['CaptchaArr'])){
				$_SESSION['CaptchaArr'] = $this->SetArr();
			}
			// print_r($_SESSION['CaptchaArr']);
		}
		public function num2adb($num){
			$text="";
			for($i=0;$i<strlen($num);$i++){
				$shift = substr($num, $i, 1);
				$char = substr($num, $i - strlen($num), 2);
				$key=$shift+$char;
				$text .= $_SESSION['CaptchaArr'][$key];
			}
			return $text;
		}
		public function creat_images($num){
			$type=2;
			header("Content-type: image/PNG");

			// 產生種子, 作圖形干擾用
			srand((double)microtime()*10000000000);

			// 產生圖檔, 及定義顏色
			$img_x = 120;
			$img_y = 28;
			$im = imageCreate($img_x, $img_y);
			//ImageColorAllocate 分配圖形的顏色
			$back = ImageColorAllocate($im, rand(200,255), rand(200,255), rand(200,255));
			
			$authText=$this->num2adb($num);
			imageFill($im, 0, 0, $back);
			// imageString($im, 5, rand(0,55), rand(0,40), $authText, $font);
			$str_x = 0;
			$str_y = 0;
			for($i = 0; $i < strlen($authText); $i++){
				$str_x += rand(10,20);
				$str_y = rand(0,$img_y/2);
				$font = ImageColorAllocate($im, rand(0,100), rand(0,100), rand(0,100));
				imageString($im, 5, $str_x, $str_y, $authText[$i], $font);
			}

			// 插入圖形干擾點共 50 點, 可插入更多, 但可能會使圖形太過混雜
			for($i = 0; $i < rand(50,200); $i++) {
				$point = ImageColorAllocate($im, rand(0,255), rand(0,255), rand(0,255));
				imagesetpixel($im, rand(0,$img_x)  , rand(0,$img_y) , $point);
			}
			for($i = 1; $i<=rand(2,5); $i++){
				$point = ImageColorAllocate($im, rand(0,255), rand(0,255), rand(0,255));
				imageline($im, rand(0,$img_x)  , rand(0,$img_y) ,rand(0,$img_x)  , rand(0,$img_y) , $point);
			}

			// 定義圖檔類型並輸入, 最後刪除記憶體
			if($type==1){
				ob_start();
				ImagePNG($im);
				$output = ob_get_contents();
				ob_end_clean();
				echo base64_encode($output);
			}else{
				ImagePNG($im);
			}
			ImageDestroy($im);
		}
		public function check_images($source,$input_code){
			$check_code=$this->num2adb($source);
			if($check_code==$input_code){
				return true;
			}else{
				return false;
			}
		}
		private function SetArr(){
			$tmp = array();
			for($i = 1; $i<199; $i++){
				$val = (rand(0,1) == 0)? rand(48,56):rand(65,90);
				$tmp[$i] = chr($val);
			}
			return $tmp;
		}
	}