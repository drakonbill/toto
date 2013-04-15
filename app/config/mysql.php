<?php
class config_mysql extends libMySQL {
	private $host = "localhost";
	private $user = "root";
	private $pass = "root";
	private $db = "meetoparty";
	private $connection;
	
	public function __construct(){
		 parent::__construct($this->host, $this->user, $this->pass, $this->db);
		
		// return $this->connection;
	}
	
        public function getConnection() {
             $this->connection =  parent::getConnection();
            return $this->connection;
        }
	
}
?>