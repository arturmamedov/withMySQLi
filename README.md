mysqli-lightLib
===============

MySQLi-lightLib is a simple and little class with basic method for work with mysqli ( http://www.obiv.it/video-giornale/7-programmazione-oggetti-php-mysqli-class.html )

***

Usage: 
=============

Retrive data:

    // require and create object
    require_once 'mysqli.php';
    $db = new Mysqli_Database('localhost', 'root', 'pass', 'db_name');

    // prepare query for execution 
    $db->prepare("SELECT * FROM `table` WHERE record='{$record}'");
    
    // execute query 
    $db->query();
    
    // fetch data 
    $row = $db->fetch('array'); 
    // $db->fetch() accept to: 'object', 'allAssoc', 'assoc'
    
Insert data:

    $data = array(
        'user_id' => NULL,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'mail' =>  $this->mail,
        'password' => md5($this->password),
        'type' => $this->type,
        'verification_key' => $this->vkey,
        'ts_lastlog' => 'CURRENT_TIMESTAMP', // todo: this dont work because quoted
        'ts_created' => '2013-10-22 00:00:00',
        'active' => '0'
    );

    $user_id = $this->_db->insert('users', $data);

***
