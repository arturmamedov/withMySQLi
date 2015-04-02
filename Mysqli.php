<?php
/**
 * @version 1.1
 * @author Artur Mamedov <arturmamedov1993@gmail.com>
 * 
 * Class for basic work with mysqli library 
 * discussed in videolessons(italian lang): http://www.obiv.it/video-giornale/7-programmazione-oggetti-php-mysqli-class.html
 * and video course(italian lang):  http://www.obiv.it/video-corso/oop-mvc-php.html
 */
class Database_Mysqli {
    /**
     * Current query
     * @var string
     */
    private $query;
    /**
     * Mysqlimproved class
     * @var object 
     */
    private $_connection = null;    
    /**
     * Result of query perform
     * @var mixed 
     */
    private $result;
    /**
     * Fetch mode
     *
     * @var string
     */
    protected $_fetchMode = 'FETCH_ASSOC';
    
    /**
     * Style fetch FETCH_ASSOC
     */
    const FETCH_ASSOC = 'FETCH_ASSOC';
    /**
     * Style fetch FETCH_OBJ
     */
    const FETCH_OBJ = 'FETCH_OBJ';
    /**
     * Style fetch FETCH_BOTH
     */
    const FETCH_BOTH = 'FETCH_BOTH';
    /**
     * Style fetch FETCH_NUM
     */
    const FETCH_NUM = 'FETCH_NUM';
    
    /**
     * The charset of the connection
     * @var string 
     */
    public $charset = 'utf8';
    
    
    public function __construct($host, $user, $password, $database, $charset = null, $port = null, $socket = null){
        // set configuration of connection
        $this->config($host, $user, $password, $database, $charset, $port, $socket);
        // connect in constructor
        $this->_connect();
    }

    public function __destruct(){
        $this->_disconnect();
    }
    
    
    /**
     * configure the connection data
     * 
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @param string $charset
     * @param string $port
     * @param string $socket
     * 
     * @return void
     */
    public function config($host = null, $user = null, $password = null, $database = null, $charset = null, $port = null, $socket = null){
        $this->host = ($host != null)?$host:$this->host;
        $this->user = ($user != null)?$user:$this->user;
        $this->password = ($password != null)?$password:$this->password;
        $this->database = ($database != null)?$database:$this->database;
        $this->charset = ($charset != null)?$charset:$this->charset;
        $this->port = ($port != null)?$port:$this->port;
        $this->socket = ($socket != null)?$socket:$this->socket;
        
        return true;
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
     */
    public function _connect(){
        if($this->_connection) {
            return;
        }
        // creare la connessione
        $this->_connection = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port, $this->socket);
        
        if(mysqli_connect_errno()){
            $this->_disconnect();
            throw new Exception('No Connect to DB error: '. mysqli_connect_error());
        } else
            $this->_connection->set_charset($this->charset);

        return true;
    }	

    /**
     * Test if a connection is active
     *
     * @return boolean
     */
    public function _isConnected(){
        return ((bool) ($this->_connection instanceof mysqli));
    }

    /**
     * Force the connection to close.
     *
     * @return void
     */
    public function _disconnect(){
        if($this->_isConnected()){
            $this->_connection->close();
        }
        $this->_connection = null;
    }
    
    /**
     * Prepares and executes an SQL statement with bound data.
     *
     * @param  string  $sql  The SQL statement with placeholders
     * @param  mixed  $bind An array of data to bind to the placeholders.
     * @return Zend_Db_Statement_Interface
     */
    public function query($sql = null){
        $this->_connect();
        
        if($sql != null)
            $this->query = $sql;
        
        $this->result = $this->_connection->query($this->query);
        
        if(!$this->result)
            throw new Exception('Mysqli_Database - MySQLi error: '.$this->_connection->error);
        
        return $this->result;
    }
    
    /**
     * Returns the underlying database connection object or resource.
     * If not presently connected, this initiates the connection.
     *
     * @return object|resource|null
     */
    public function getConnection()
    {
        $this->_connect();
        return $this->_connection;
    }
    
    /**
     * return current query
     * @return string
     */
    public function getQuery(){
        return $this->query;
    }
    
    
    
/* START - Fetch Methods */
    
    
    /**
     * Fetches a row from the result set.
     *
     * @param string $style  OPTIONAL Fetch mode for this fetch operation.
     * @param object $result OPTIONAL The result mysqli object to fetch.
     * 
     * @return mixed Array, object, or scalar depending on fetch mode.
     * 
     * @throws Zend_Db_Statement_Mysqli_Exception
     */
    public function fetch($style = null, $result = null)
    {
        // set a result if passed
        if($result != null) {
            $this->result = $result;
        }
        
        // make sure we have a fetch mode
        if($style === null) {
            $style = $this->_fetchMode;
        }

        // fetch or throw Exception
        $row = false;
        switch ($style) {
            case'FETCH_NUM':
                $row = $this->result->num_rows;
                break;
            case'FETCH_ASSOC':
                $row = $this->result->fetch_assoc();
            break;
            case'FETCH_BOTH':
                $row = $this->result->fetch_array();
            break;
            case'FETCH_OBJ':
                $row = $this->result->fetch_object();
            break;
            default:
                throw new Exception("Invalid fetch mode '$style' specified");
        }
        
        // return data
        return $row;
    }    
    
    /**
     * Retrive data from db for the passed query and fetch/return one field
     * 
     * @param string $query the query to execute
     * 
     * return string one field data
     */
    public function fetchOne($sql)
    {
        $this->query($sql); // retrive data
        
        $res = $this->result->fetch_row();
        
        return $res[0];
    }
    
    /**
     * Retrive data from db for the passed query and fetch/return one row
     * 
     * @param string $query the query to execute
     * 
     * return string one field data
     */
    public function fetchRow($sql)
    {
        $this->query($sql); // retrive data
        
        $res = $this->result->fetch_row();
        
        return $res;
    }
    
    /**
     * Fetches a all rows from the result set.
     *
     * @param string $style  OPTIONAL Fetch mode for this fetch operation.
     * @param object $result OPTIONAL The result mysqli object to fetch.
     * 
     * @return mixed Array, object, or scalar depending on fetch mode.
     * 
     * @throws Zend_Db_Statement_Mysqli_Exception
     */
    public function fetchAll($style = null, $result = null)
    {    
        // set a result if passed
        if($result != null) {
            $this->result = $result;
        }
        
        // make sure we have a fetch mode
        if($style === null) {
            $style = $this->_fetchMode;
        }

        // fetch or throw Exception
        $row = false;
        switch ($style) {
            case'FETCH_ASSOC':
                while($res = $this->result->fetch_assoc()){
                    $row[] = $res;
                }
            break;
            case'FETCH_BOTH':
                while($res = $this->result->fetch_array()){
                    $row[] = $res;
                }
            break;
            case'FETCH_OBJ':
                while($res = $this->result->fetch_object()){
                    $row[] = $res;
                }
            break;
            default:
                throw new Exception("Invalid fetch mode '$style' specified");
        }
        
        // return data
        return $row;
    }   

/* END - Fetch Methods */
    
    
    
/* START - InnoDb Transictions */
    
    /**
     * Begin a transaction.
     *
     * @return boolean
     */
    public function _beginTransaction(){
        $this->_connect();
        $this->_connection->autocommit(false);
        return true;
    }
    
    
    /**
     * Commit a transaction.
     *
     * @return void
     */
    public function _commit(){
        $this->_connect();
        $this->_connection->commit();
        $this->_connection->autocommit(true);
    }
    
    /**
     * Roll-back a transaction.
     *
     * @return void
     */
    public function _rollBack(){
        $this->_connect();
        $this->_connection->rollback();
        $this->_connection->autocommit(true);
    }
    
/* END - InnoDb Transictions */
    
    
/* START - C_R_UD methods */
    
    /**
     * Insert data into db
     * array:
     * ex: $this->insert($table, array('user_id' => NULL, 'first_name' => $this->first_name, 'last_name' => $this->last_name);
     * are quete into and escaped by $this->escape
     * 
     * @todo: only string to improve
     * or only query string ex: $this->insert("INSERT INTO users (user_id, first_name, last_name) VALUES (NULL , 'Artur', 'Mamedov'")
     * 
     * 
     * @param string $table (table_name)
     * @param array $data ('column1' => 'value1', 'column2' => 'value2')
     * 
     * @return boolean/int  false if an error occur, last insert id if all ok
     */
    public function insert($table, $data){
        if(count($data) > 0){
            // escape all passed data values
            $i=0;
            $columns = '';
            $values = '';
            foreach($data as $col => $val){
                $columns .= $col;
                $values .= ($val == NULL) ? 'NULL' : '\''.$this->escape($val).'\'';
                
                // add ', ' if this not last col => val
                if($i+1 != count($data)){
                    $columns .= ', '; // implode(', ', $columns)
                    $values .= ', '; // implode(', ', $values)
                }
                $i++;
            }
            
            $query = "INSERT INTO {$table} ({$columns}) VALUES ($values)";
            
            $this->query($query);
        } else {
            $this->query($data);
        }
        
        return $this->_connection->insert_id;
    }
    

    /**
     * Updates table rows with specified data based on a WHERE clause.
     *
     * @param  mixed        $table The table to update.
     * @param  array        $bind  Column-value pairs.
     * @param  mixed        $where UPDATE WHERE clause(s).
     * 
     * @return int          The number of affected rows.
     * 
     * @throws
     */
    public function update($table, array $bind, $where = '')
    {
        /**
         * Build "col = val" pairs
         */
        foreach($bind as $col => $val) {
            $set[] = "{$col} = '{$val}'";
        }
            
        /**
         * Build the UPDATE statement
         */
        $sql = "UPDATE {$table} SET ". implode(', ', $set) . (($where) ? " WHERE $where" : '');
        /**
         * Execute the statement and return the number of affected rows
         */
        $this->result = $this->query($sql);
            
        if(($result = $this->_connection->affected_rows) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Deletes table rows based on a WHERE clause.
     *
     * @param  mixed        $table The table to update.
     * @param  mixed        $where DELETE WHERE clause(s).
     * @return int          The number of affected rows.
     */
    public function delete($table, $where = '')
    {
        /**
         * Build the DELETE statement
         */
        $sql = "DELETE FROM {$table} " . (($where) ? " WHERE $where" : '');

        /**
         * Execute the statement and return the number of affected rows
         */
        $this->result = $this->query($sql);
        if(($result = $this->_connection->affected_rows) > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    
/* START - abstract methods */ 
    
    /**
     * when code set a undefined proprieties add him
     * 
     * @param string $name
     * @param string $value
     * 
     * @return boolean
     */
    public function __set($name, $value){
        if(isset($this->$name) || $this->$name == null){
            $this->$name = $value;
            return true;
        }

        return false;
    }

    
    
    /**
     * when code try to get undefined proprieties return null
     * 
     * @param string $name
     * 
     * @return NULL/mixed or _proprieties or NULL
     */
    public function __get($name){
        return (isset($this->$name)) ? $this->$name : null;
    }
    
    /**
     * Escape passed data for prevent injection
     * 
     * @param string $data
     * 
     * @return string escaped $data 
     */
    public function escape($data){
        return $this->_connection->real_escape_string($data);
    }
    
/* END - abstract methods */
}
?>