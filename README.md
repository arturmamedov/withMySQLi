Connect and CRUD withMySQLi
===============
withMySQLi - is a simple and little class with basic method for work with mysqli 
C - Create: by INSERT $db->insert($table, $data_array); // name table, array with pairs, and return last id
R - Read: by SELECT dirretcly with query for a moment ... with good $db->fetch($style);
U - Update: $db->update($table, $data_array, $where); // return affected rows
D - Delete: $db->delete($table, $where); // return affected rows


( http://www.obiv.it/video-giornale/7-programmazione-oggetti-php-mysqli-class.html )

***
***

Usage: 
=============

#### Start

```php
    // require and create object
    require_once 'Mysqli.php';
    $db = new Mysqli_Database('localhost', 'root', 'pass', 'db_name'); // charset, port, socket
```

---

#### READ data:

```php
    // execute query 
    $db->query("SELECT * FROM `table` WHERE record='{$record}'");

    // fetch data default FETCH_ASSOC
    $row = $db->fetch(); // FETCH_NUM, FETCH_BOTH, FETCH_OBJ
    /* 
        there are to
        $db->fetchOne("SELECT * FROM `table` WHERE record='{$record}'"); // for only one field
        $db->fetchRow("SELECT * FROM `table` WHERE record='{$record}'"); // for only one row
    */
```

---
    
#### INSERT data:

```php
    $data = array(
        'user_id' => NULL,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'mail' =>  $this->mail,
        'password' => md5($this->password),
        'type' => $this->type,
        'active' => '0'
    );

    $user_id = $this->_db->insert('users', $data);
```

---
    
#### UPDATE data:

```php
    $data = array(
            'mail' => 'artik2007@ukr.net',
            'name' => 'Ciro'
        );

    $this->db->update('users', $data, "user_id = {$user->user_id}");
```

---
    
#### DELETE data:

```php
    $this->db->delete('users', 'user_id = 12'); // one row with where

    $this->db->delete('users1'); // entire table !!! because without WHERE
```

***