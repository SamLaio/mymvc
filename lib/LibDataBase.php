<?php
class LibDataBase {
	public $dbtype, $dbhost,$dbuser,$dbpass,$dbname,$table;
	public $count = 0;
	public $install = false;

	//共用function
	function __construct() {
		$this->dbtype = DbType;
		if (DbType == 'mysql') {
			$this->dbhost = DbHost;
			$this->dbuser = DbUser;
			$this->dbpass = DbPw;
			$this->dbname = DbName;
		}
		if (DbType == 'sqlite') {
			$this->dbname = DbName;
		}
	}
	protected function comb($sub1, $sub2) {
		$re = false;
		if (is_array($sub1)) {
			$re = implode($sub2,array_values($sub1));
		} else {
			$re = $sub1;
		}
		if(!$re)
			$re = $this->table;
		return $re;
	}

	public function Link() {
		//test link add by Sam 20140805
		$link = false;
		
		if ($this->dbtype == 'mysql' and $this->chkservice($this->dbhost, 3306)) {
			$to_host = $this->dbhost;
			$to_user = $this->dbuser;
			$to_pass = $this->dbpass;
			
			$link = new PDO(
					"mysql:host=$to_host;dbname=" . $this->dbname, $to_user, $to_pass, [
						PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
					]
			);
		}
		if ($this->dbtype == 'sqlite') {
			//echo $this -> dbname;exit;
			$link = new PDO("sqlite:" . $this->dbname);
			if (!$link) die ($error);
		}
		//test link add by Sam 20140805
		if($link)
			return $link;
		else{
			echo 'DB link is false.';
			exit;
		}
	}

	//測試連線
	private function chkservice($host, $port) {
		
		$ch_ini_display = (ini_get('display_errors') == 1);
		if ($ch_ini_display) //判斷ini的display errors的設定
			ini_set('display_errors', 0); //設定連線錯誤時不要display errors
		$x = fsockopen(gethostbyname($host), $port, $errno, $errstr, 1);
		if ($ch_ini_display)
			ini_set('display_errors', 1); //將ini的display error設定改回來
		if (!$x){//測試連線
			
			return false;
		}else {
			fclose($x);
			return true;
		}
	}
	//共用function end
	//語法組合
	public function Select($table, $field, $req = false, $or_by = false, $limit = false) {
		$table = $this->comb($table, ', ');
		$field = $this->comb($field, ', ');
		$req = ($req)?'where ' . $req:'';
		$or_by = ($or_by)?" order by " . $this->comb($or_by, ', '):'';
		$limit = ($limit)?" limit " . $limit:'';
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
		echo $sql;
		$link = $this->Link();
		$link->query($sql);
		$link = null;
	}

	public function Fetch($sql) {
		$link = $this->Link();
		$this->count = 0;
		$query = $link->query($sql);
		$this->count = count($query);
		$query = $query->fetchAll();
		$link = null;
		return $this->ValDecode(query);
	}

	public function Assoc($sql,$field = false, $req = false, $or_by = false, $limit = false) {
		if($field)
			$sql = $this->Select($sql,$field, $req, $or_by, $limit);
		//echo $sql;exit;
		$link = $this->Link();
		//print_r($link);exit;
		$re = $link->query($sql);
		$re->setFetchMode(PDO::FETCH_ASSOC);
		$re = $re->fetchAll();
		$this->count = count($re);
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