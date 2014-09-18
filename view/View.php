<?php
	class View {
		private $isPw = false;
		function __construct($page) {
			include_once 'hand.html';
			//include $page.'.html';
			echo $this->getBody('view/'.$page.'.html');
			if($this->isPw)
				echo $this->code_str();
			include_once 'foot.html';
			//print_r($this->code_str());
		}
		public function code_str(){
			$re_arr = array();
			for($i = 33; $i <=126; $i++){
				$t = urlencode(chr(rand(33,126)));
				if(!in_array($t,$re_arr))
					$re_arr[urlencode(chr($i))] = $t;
				else
					$i-=1;
			}
			foreach($re_arr as $key => $value){
				
			}
			return $re_arr;
		}
		private function getBody($filename){
			//echo 11;
			$str = "";
			//判斷是否有該檔案
			if(file_exists($filename)){
				$file = fopen($filename, "r");
				if($file != NULL){
					//當檔案未執行到最後一筆，迴圈繼續執行(fgets一次抓一行)
					while (!feof($file)) {
						$tmp = fgets($file);
						if(stristr ($tmp,'password'))
								$this->isPw = true;
						$str .= $tmp;
					}
					fclose($file);
				}
			}else
				echo 12;
			return $str;
		}
	}
?>