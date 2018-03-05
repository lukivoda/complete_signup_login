<?php
/*   ACTIVE RECORDS PATTERN    */
/*   ACTIVE RECORDS PATTERN    */
/*   ACTIVE RECORDS PATTERN    */
namespace App;

class Main extends Db
{


//    function makeFields() {
//        $db = Db::getConnection();
//        $fields = "";
//        $keyColumn = static::$key;
//        foreach ( $this as $fieldKey => $fieldValue) {
//            if ($fieldKey == $keyColumn) continue;
//            $fields .= $fieldKey . '=' . $db->quote($fieldValue) . ',';
//        }
//        $fields = rtrim($fields,",");
//        return $fields;
//    }


    function makeFields() {
        $db = Db::getConnection();
        $fields = "";
        $keyColumn = static::$key;
        foreach ( $this as $fieldKey => $fieldValue) {
            if ($fieldKey == $keyColumn) continue;
            $fields .= $fieldKey . '=:' . $fieldKey . ',';
        }
        $fields = rtrim($fields,",");
        return $fields;
    }


    public function update($id) {
        $db = Db::getConnection();
        $tabela = static::$table;
        $key = static::$key;
        //$keyF = static::$key;
        $sql = "UPDATE {$tabela} SET ". $this->makeFields() . " WHERE {$key} = :id";
        $statement = $db->prepare($sql);
        $id_array = array(':id'=>$id);
        $complete_array = $this->array_exec();
        $complete_array =array_merge($id_array,$complete_array);
        $statement->execute($complete_array);

        if($statement->rowCount()>0 ){
            return true;
        }else{
            return false;
        }


    }

    public  function delete($id) {
        $db = Db::getConnection();
        $tabela = static::$table;
        $key = static::$key;
        try{
            $deleteQuery = "DELETE FROM {$tabela} WHERE {$key} = :id";
            $statement = $db->prepare($deleteQuery);
            $statement->execute(array(':id'=>$id));

            if($statement->rowCount()>0 ){
                return true;
            }else{
                return false;
            }

        }catch (PDOException $ex){
            echo "An error occured ".$ex->getMessage();
        }
    }

    public static function get($filter = "") {
        $db = Db::getConnection();
        $tabela = static::$table;
        $res = $db->query("SELECT * FROM {$tabela} {$filter}");
        $res->setFetchMode(PDO::FETCH_CLASS,get_called_class());
        $output = [];
        while ($rw = $res->fetch()) {
            $output[] = $rw;
        }
        return $output;
    }

    public  function getById($id) {
        $db = Db::getConnection();
        $tabela = static::$table;
        $key = static::$key;
        $sql = "SELECT * FROM {$tabela} WHERE {$key} = :id ";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS,get_called_class());
        return $statement->fetch();


    }

//    public function save() {
//        $db = Db::getConnection();
//        $tabela = static::$table;
//        $upit = "INSERT INTO {$tabela} SET " . $this->makeFields();
//        $db->exec($upit);
//        $kljucnaKolona = static::$key;
//        $this->$kljucnaKolona = $db->lastInsertId();
//       // $r = get_object_vars($this);
//
//
//    }



   public function array_exec(){
       $arr_keys = array_keys(get_object_vars($this));
       $arr_values = array_values(get_object_vars($this));
       $arr_keys = array_slice($arr_keys, 1);
       foreach($arr_keys as $key=> $value){
           $arr_keys[$key] = ":".$value;
       }
       $arr_values = array_slice($arr_values, 1);
       $complete_array = array_combine( $arr_keys,$arr_values);

       return $complete_array;

   }


    public function save() {
        $db = Db::getConnection();
        $tabela = static::$table;

        try {
            $upit = "INSERT INTO {$tabela} SET " . $this->makeFields();
            $statement = $db->prepare($upit);
            $complete_array = $this->array_exec();

            $statement->execute($complete_array);
            $kljucnaKolona = static::$key;
            $this->$kljucnaKolona = $db->lastInsertId();

            if($statement->rowCount()>0){
                return true;
            }else{
                return false;
            }

        }catch(PDOException $ex){
            echo "An error occured ".$ex->getMessage();
        }
        // $r = get_object_vars($this);


    }

    public static function getJson($filter = "") {
        $db = Db::getConnection();
        $tabela = static::$table;
        $res = $db->query("SELECT * FROM {$tabela}{$filter}");
        $res->setFetchMode(PDO::FETCH_CLASS,get_called_class());
        $output = [];
        while ($rw = $res->fetch()) {
            $output[] = $rw;
        }
        return json_encode($output);
    }

    public static function getJsonById($id) {
        $db = Db::getConnection();
        $db->query("SET NAMES utf8");
        $tabela = static::$table;
        $key = static::$key;
        $res = $db->query("SELECT * FROM {$tabela} WHERE {$key} = {$id}");
        $output = [];
        while ($rw = $res->fetch(PDO::FETCH_OBJ)) {
            $output[] = $rw;
        }
        return json_encode($output);
    }
}