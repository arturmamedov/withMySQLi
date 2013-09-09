<?php

class Mysqlimproved {
	// contiene la query
	private $query;
	// connessione e risorsa mysqli
	private $mysqli;
	// risultato con i dati estratti
	private $result;
	
	public function __construct(){
		// Parametri di connessione
		$host = 'localhost';
		$user = 'root';
		$password = 'qweqwe';
		$database = 'fabwebsite';
		$charset = 'utf8';
		
		// connessione in construct per non chiamare il metodo
		$this->connect($host, $user, $password, $database, $charset);
	}
	
	public function __destruct(){
		$this->disconnect();
	}

// Metodo per la connessione
	public function connect($host, $user, $password, $database, $charset, $port = null, $socket = null){
		// creare la connessione
		$this->mysqli = new mysqli($host, $user, $password, $database, $port, $socket);
		
		if(mysqli_connect_error()){
			exit('Impossibile connettersi al DataBase'.$mysqli_connect_error);
		} else {
			$this->mysqli->set_charset($charset);
		}
		
		return true;
	}	
// Per Disconnetterci
	public function disconnect(){
		$this->mysqli->close();
		return true;
	}
	
// Per prepaparare la query
	public function prepare($query){
		$this->query = $query;
		return true;
	}
	
// Per eseguire la query
	public function query(){
		if(isset($this->query)){
			$this->result = $this->mysqli->query($this->query);
			if(!$this->result) {
				exit("Errore nell'interrogazione del DataBase: ".$this->mysqli->error);
				return false;
			}
		}	
	}

// Per fettchare , estrare i dati in un Array o Oggetto
	public function fetch($type = 'object'){
		switch($type){
			case 'array':
				$row = $this->result->fetch_array();
				break;
			case 'object':
				$row = $this->result->fetch_object();
				break;
		}
		$this->result->close();
		
		return $row;
	}

// Per ri pulire i dati
	public function escape($data){
		return $this->mysqli->real_escape_string($data);
	}
}
?>
