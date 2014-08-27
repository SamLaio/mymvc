<?php
class LibDataBase{
	//public $dbhost = '192.168.248.25';
	public $dbhost = '192.168.247.33';
	public $dbuser = 'phitech';
	public $dbpass = 'phitech123';
	
	//public $p_dbhost = '192.168.248.26';
	/*public $p_dbhost = '192.168.247.34';
	public $p_dbuser = 'phitech';
	public $p_dbpass = 'phitech123';*/
	public $dbname;
	public $table;
	public $sql_count = 0;
	private $sql_change = true;
	
	//共用function
	protected function comb($sub1, $sub2){
		$re = '';
		$sub3 = '';
		if(is_array ($sub1)){
			foreach($sub1 as $value){
				$re .= $sub3 . $value;
				$sub3 = $sub2;
			}
		}else{
			$re = $sub1;
		}
		return $re;
	}
	private function Link(){
		//test link add by Sam 20140805
		$to_host = $this->dbhost;
		$to_user = $this->dbuser;
		$to_pass = $this->dbpass;
		/*if(!$this->chkservice($this->dbhost,3306)){
			$to_host = $this->p_dbhost;
			$to_user = $this->p_dbuser;
			$to_pass = $this->p_dbpass;
		}*/
		$link = new PDO(
				"mysql:host=$to_host;",
				$to_user,
				$to_pass,
				array(
					PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
				)
		);
		/*if($to_host != $this->dbhost){
			$sql = "insert into news.db_log (`date`) values ('".date('Y-m-d H:i:s')."');";
			$link->query($sql);
		}else{
			
		}*/
		//test link add by Sam 20140805
		return $link;
	}
	//測試連線
	private function chkservice($host, $port){
		$ch_ini_display=(ini_get('display_errors')==1);
		if ($ch_ini_display) //判斷ini的display errors的設定
			ini_set('display_errors', 0);//設定連線錯誤時不要display errors
		$x = fsockopen(gethostbyname($host), $port, $errno, $errstr, 1);
		if ($ch_ini_display)
			ini_set('display_errors', 1); //將ini的display error設定改回來
		if (!$x)//測試連線
			return false;
		else{
			fclose($x);
			return true;
		}
	}
	//共用function end
	
	//語法組合
	public function Select( $table, $field, $req = '',$or_by = '', $limit = ''){
		$table = $this->comb($table, ', ');
		$field = $this->comb($field, ', ');
		if($req != '')
			$req =  'where ' . $req;
		if($or_by != '')
			$or_by = " order by " . $this->comb($or_by, ', ');
		if($limit != '')
			$limit = " limit " . $limit;
		$sql = "select $field from $table $req $or_by $limit;";
		return $sql;
	}
	
	public function In( $table, $field, $value){
		$field = '('.$this->comb($field, ', ') . ')';
		$value = "(" . $this->comb($value, ", "). ')';
		$sql = "insert into $table $field values $value;";
		return $sql;
	}
	
	public function Del( $table, $req = ''){
		//DELETE FROM [TABLE NAME] WHERE 條件;
		$table = $this->comb($table, ', ');
		if($req != '')
			$req = 'where ' . $req;
		$sql = "DELETE FROM $table $req;";
		return $sql;
	}
	
	public function sql_up($table, $value, $req){
		//UPDATE [TABLE NAME] SET [欄名1]=值1, [欄名2]=值2, …… WHERE 條件;
		$value = $this->comb($value, ", ");
		$req = 'where ' . $req;
		$sql = "update $table set $value $req;";
		return $sql;
	}
	//語法組合 end
	
	//sql執行
	public function Query($sql){
		/*if($this -> sql_change)
			$this->p_db($sql);*/
		$link = $this -> Link();
		$link->query($sql);
		$link = null;
	}
	
	public function Fetch($sql){
		$link = $this -> Link();
		$this -> sql_count = 0;
		$query = $link->query($sql);
		$this->sql_count = count($query);
		$data = $query;
		$link = null;
		return $data;
	}
	
	public function Assoc($sql){
		$link = $this -> Link();
		$re = $link->query($sql);
		//print_r($re);
		$re -> setFetchMode(PDO::FETCH_ASSOC);
		$re = $re-> fetchAll();
		$this->sql_count = count($re);
		$link = null;
		return $re;
	}
	//sql執行 end
	
	//備援 已不使用，改為mysql自動同步 by Sam 20140805
	/*private function p_db($sql){
		if(stristr ($sql, 'insert') or stristr ($sql, 'DELETE') or  stristr ($sql, 'update')){
			$link = new PDO(
						"mysql:host=$this->p_dbhost;dbname=$this->dbname",
						$this -> dbuser,
						$this -> dbpass,
						array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
				);
			$link->query($sql);
			$link = null;
		}
	}*/
	//備援
}
?>