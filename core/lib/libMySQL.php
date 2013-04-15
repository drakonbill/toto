<?php
class libMySQL{
	private $host;
	private $user;
	private $pass;
	private $db;
	private $connection;
	
	public function __construct($host, $user, $pass, $db){
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
		
	}
	
	public function getConnection(){
		$this->connection = mysql_connect($this->host,$this->user,$this->pass, true);
		mysql_select_db($this->db, $this->connection);
		return $this->connection;
	}
	
	public function __destruct(){
		mysql_close($this->connection);
	}
}
?>