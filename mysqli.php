<?php

class Mysqlimproved {
	private $query;
	private $mysqli;
	private $result;
	
	public function __construct(){
		// connection param
		$host = 'localhost';
		$user = 'root';
		$password = 'qweqwe';
		$database = 'fabwebsite';
		$charset = 'utf8';
		
		// connect in constructor
		$this->connect($host, $user, $password, $database, $charset);
	}
	
	public function __destruct(){
		$this->disconnect();
	}

	/** 
	 * Method for create connection with DB
	 * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @param string $charset
     * @param string $port
     * @param string $socket
     * 
     * @return boolean
     * /
	public function connect($host, $user, $password, $database, $charset, $port = null, $socket = null){
		// creare la connessione
		$this->mysqli = new mysqli($host, $user, $password, $database, $port, $socket);
		
		if(mysqli_connect_error()){
			return array('status' => false, 'error' => $mysqli_connect_error);
		} else {
			$this->mysqli->set_charset($charset);
		}
		
		return true;
	}	
	
	/**
	 * Close connection with DB
	 * /
	public function disconnect(){
		$this->mysqli->close();
		return true;
	}
	
	/**
	 * Prepare query for execution
	 * 
	 * @param string $query
	 * 
	 * @return boolean
	 * /
	public function prepare($query){
		$this->query = $query;
		return true;
	}
	
	/**
	 * Execute prepared query 
	 * 
	 * @return boolean (false on error)
	 * /
	public function query(){
		if(isset($this->query)){
			$this->result = $this->mysqli->query($this->query);
			if(!$this->result) {
				return array('status' => false, 'error' => $this->mysqli->error);
			}
		}	
	}

	/**
	 * For fetch result data
	 * 
	 * @param string $type ('object', 'allAssoc', 'assoc', 'array')
	 * 
	 * @return mixed (fetched result) 
	 * 
	 * 'assoc', 'object' and 'array': type fetch only one row
	 * 'allAssoc': fetch all row how associative array
	 * /
	public function fetch($type = 'object'){
		switch($type){
			case 'allAssoc':
				while($res = $this->result->fetch_assoc()){
					$row[] = $res;
				}
				break;
			case 'assoc':
				$row = $this->result->fetch_assoc();
				break;
			case 'object':
				$row = $this->result->fetch_object();
				break;
			case 'array':
				$row = $this->result->fetch_array();
				break;	
		}
		$this->result->close();
		
		return $row;
	}

	/**
	 * Escape passed data for prevent injection
	 * 
	 * @param string $data
	 * 
	 * @return string escaped $data 
	 * /
	public function escape($data){
		return $this->mysqli->real_escape_string($data);
	}
}
?>
