Connect and CRUD withMySQLi
===============
withMySQLi - is a simple and little class with basic method for work with mysqli

C - Create: by INSERT $db->insert($table, $data_array); // name table, array with pairs, and return last id

R - Read: by SELECT dirretcly with query for a moment ... with good $db->fetch($style);

U - Update: $db->update($table, $data_array, $where); // return affected rows

D - Delete: $db->delete($table, $where); // return affected rows


( ita videolessons: http://www.obiv.it/video-giornale/7-programmazione-oggetti-php-mysqli-class.html )

***
***

Usage: 
=============

##### Start: $db

```php
    // require and create object
    require_once 'Mysqli.php';
    $db = new Mysqli_Database('localhost', 'root', 'pass', 'db_name'); // charset, port, socket
```

---

##### READ data: $db->fetch();

```php
    // execute query 
    $db->query("SELECT * FROM `table` WHERE record='{$record}'");

    // fetch data default FETCH_ASSOC
    $row = $db->fetch(); // default: FETCH_ASSOC; other: FETCH_NUM, FETCH_BOTH, FETCH_OBJ ex: $db->fetch('FETCH_OBJ');
    /* 
        there are another two:
        $db->fetchOne("SELECT * FROM `table` WHERE record='{$record}'"); // for only one field
        $db->fetchRow("SELECT * FROM `table` WHERE record='{$record}'"); // for only one row
        pass to him the sql query and get result ;)
    */

    $rows = $db->fetchAll(); // default: FETCH_ASSOC; other: FETCH_BOTH, FETCH_OBJ ex: $db->fetch('FETCH_OBJ');
```

---
    
##### INSERT data: $db->insert($table, $data);

```php
    //* insert( string $table , array $data);
    $data = array(
        'user_id' => NULL,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'mail' =>  $this->mail,
        'password' => md5($this->password),
        'type' => $this->type,
        'active' => '0'
    );

    $user_id = $db->insert('users', $data); // return last inserted id
```

---
    
##### UPDATE data: $db->update($table, $data, $where);

```php
    //* update( string $table , array $data, string $where);
    $data = array(
            'mail' => 'artik2007@ukr.net',
            'name' => 'Ciro'
        );

    $db->update('users', $data, "user_id = {$user->user_id}");
```

---
    
##### DELETE data: $db->delete($table, $where);

```php
    //* delete( string $table , [string $where]);
    $db->delete('users', 'user_id = 12'); // one row with where

    $db->delete('users1'); // entire table !!! because without WHERE
```

***
