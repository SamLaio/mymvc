<?php
	class View {
		private $isPw = false;
		function __construct($page) {
			include_once 'hand.html';
			//include $page.'.html';
			echo $this->getBody('view/'.$page.'.html');
			if($this->isPw){
				echo $this->PwEnCode();
			}
			include_once 'foot.html';
		}
		public function PwEnCode(){
			$re_arr = array();
			for($i = 33; $i <=126; $i++){
				$t = urlencode(chr(rand(33,126)));
				if(!in_array($t,$re_arr))
					$re_arr[urlencode(chr($i))] = $t;
				else
					$i-=1;
			}
			$tmp=array();
			foreach($re_arr as $key => $value){
				$tmp[] = array('id'=>$key, 'val'=>$value);
			}
			if(!isset($_SESSION))
				session_start ();
			$_SESSION['PwEnCode']=$tmp;
			$tmp = array();
			$_SESSION['PwHand'] = rand(3,5);
			for($i = 1; $i<= $_SESSION['PwHand'];$i++){
				$tmp[] = chr(rand(65,90));
			}
			$_SESSION['PwHand'] = implode($tmp).'::';
			return "
	<script>
		var pwEnCode = ".json_encode($_SESSION['PwEnCode']).";
		$('input').change(function(){
			if($(this)[0].type == 'password'){
				var tmp = '';
				for(var i = 0; i < $(this).val().length; i++){
					var str = $(this).val()[i];
					if(str == '/' || str == '@' || str == '+')
						str = encodeURIComponent(str);
					else
						str = escape(str);
					if(str == '*')
						str ='%2A';
					for(var j = 0; j < pwEnCode.length; j++){
						if(str == pwEnCode[j].id){
							if(tmp != '')
								tmp += '*|*';
							tmp += pwEnCode[j].val;
						}
					}
				}
				this.value = '".$_SESSION['PwHand']."'+tmp;
			}
		});
	</script>";
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
						//$PwFn='';
						if(stristr ($tmp,'password')){
							$this->isPw = true;
							//$tmp .= "<input type = 'test' id = ''"
						}
						$str .= $tmp;
					}
					fclose($file);
				}
			}
			return $str;
		}
	}
?>