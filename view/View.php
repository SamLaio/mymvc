<?php
class View {
	private $isPw = false;
	function __construct($page) {
		include_once 'hand.html';
		include "view/$page.html";
		if($this->getBody('view/'.$page.'.html')){
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
		$re=false;
		if(file_exists($filename)){
			$file = fopen($filename, "r");
			if($file != NULL){
				while (!feof($file)) {
					if(stristr (fgets($file),'password')){
						$re = true;
					}
				}
				fclose($file);
			}
		}
		return $re;
	}
}