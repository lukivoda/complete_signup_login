<?php

namespace App;

use PDO;

class Db {
private static $conn =  null;

    public static function getConnection(){
    $options= array(PDO::ATTR_PERSISTENT => true);
    try {
    if (!self::$conn) {
    self::$conn = new PDO(DSN, USER, PASS,$options);
    self::$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    return self::$conn;
    }catch(PDOException $ex){
    echo "Database occured ".$ex->getMessage();
    }
    }

}