<?php

class LibDataBase {
	public $dbtype, $dbhost,$dbuser,$dbpass,$dbname,$table;
	public $sql_count = 0;
	public $install = false;

	//共用function
	function __construct() {
		if(file_exists('lib/Config.php')){
			include_once 'lib/Config.php';
			if (!isset($DbType) ) {
				$this->install = true;
			}else{
				$this->dbtype = $DbType;
				if ($DbType == 'mysql') {
					$this->dbhost = $DbHost;
					$this->dbuser = $DbUser;
					$this->dbpass = $DbPw;
					$this->dbname = $DbName;
				}
				if ($DbType == 'sqlite') {
					$this->dbname = $DbName;
				}
			}
			
		}else{
			$this->install = true;
		}
	}
	protected function comb($sub1, $sub2) {
		$re = '';
		$sub3 = '';
		if (is_array($sub1)) {
			foreach ($sub1 as $value) {
				$re .= $sub3 . $value;
				$sub3 = $sub2;
			}
		} else {
			$re = $sub1;
		}
		return $re;
	}

	public function Link() {
		//test link add by Sam 20140805
		$link = false;
		if ($this->dbtype == 'mysql') {
			$to_host = $this->dbhost;
			$to_user = $this->dbuser;
			$to_pass = $this->dbpass;
			if (!$this->chkservice($this->dbhost, 3306)) {
				echo 'MySql unlink.';
				exit;
			}
			$link = new PDO(
					"mysql:host=$to_host;dbname=" . $this->dbname, $to_user, $to_pass, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
					]
			);
		}
		if ($this->dbtype == 'sqlite') {
			//echo $this -> dbname;
			$link = new PDO("sqlite:" . $this->dbname);
		}
		//test link add by Sam 20140805
		if($link)
			return $link;
		else
			echo 'DB link is false.';
	}

	//測試連線
	private function chkservice($host, $port) {
		$ch_ini_display = (ini_get('display_errors') == 1);
		if ($ch_ini_display) //判斷ini的display errors的設定
			ini_set('display_errors', 0); //設定連線錯誤時不要display errors
		$x = fsockopen(gethostbyname($host), $port, $errno, $errstr, 1);
		if ($ch_ini_display)
			ini_set('display_errors', 1); //將ini的display error設定改回來
		if (!$x)//測試連線
			return false;
		else {
			fclose($x);
			return true;
		}
	}
	//共用function end
	//語法組合
	public function Select($table, $field, $req = '', $or_by = '', $limit = '') {
		$table = $this->comb($table, ', ');
		$field = $this->comb($field, ', ');
		if ($req != '')
			$req = 'where ' . $req;
		if ($or_by != '')
			$or_by = " order by " . $this->comb($or_by, ', ');
		if ($limit != '')
			$limit = " limit " . $limit;
		$sql = "select $field from $table $req $or_by $limit;";
		return $sql;
	}

	public function In($table, $field, $value) {
		$field = '(' . $this->comb($field, ', ') . ')';
		$value = "(" . $this->comb($value, ", ") . ')';
		$sql = "insert into $table $field values $value;";
		return $sql;
	}

	public function Del($table, $req = '') {
		//DELETE FROM [TABLE NAME] WHERE 條件;
		$table = $this->comb($table, ', ');
		if ($req != '')
			$req = 'where ' . $req;
		$sql = "DELETE FROM $table $req;";
		return $sql;
	}

	public function Up($table, $value, $req) {
		//UPDATE [TABLE NAME] SET [欄名1]=值1, [欄名2]=值2, …… WHERE 條件;
		$value = $this->comb($value, ", ");
		$req = 'where ' . $req;
		$sql = "update $table set $value $req;";
		return $sql;
	}
	//語法組合 end
	//sql執行
	public function Query($sql) {
		$link = $this->Link();
		$link->query($sql);
		$link = null;
	}

	public function Fetch($sql) {
		$link = $this->Link();
		$this->sql_count = 0;
		$query = $link->query($sql);
		$this->sql_count = count($query);
		$query = $query->fetchAll();
		$link = null;
		return $this->ValDecode(query);
	}

	public function Assoc($sql) {
		$link = $this->Link();
		$re = $link->query($sql);
		$re->setFetchMode(PDO::FETCH_ASSOC);
		$re = $re->fetchAll();
		$this->sql_count = count($re);
		$link = null;
		
		return $this->ValDecode($re);
	}
	private function html_decode($body){
		$body = str_replace ( '@&4', ">", $body);
		$body = str_replace ( '@&3', "<", $body);
		$body = str_replace ( '@&2', '"', $body);
		$body = str_replace ( '@&1', "'", $body);
		$body = str_replace ( '@&5', "&", $body);
		return $body;
	}
	private function ValDecode($arr){
		if(is_array($arr)){
			foreach($arr as $key2 => $value2)
				$arr[$key2] = $this->ValDecode($value2);
		}else{
			$arr = stripslashes($arr);
			$arr = $this->html_decode($arr);
		}
		return $arr;
	}
}

?>