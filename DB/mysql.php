<?php

class mysql  extends  DB{
    public $sql_createDatabase = '';
    
    function connect(){
        parent::connect();
        $dbstring = '';
        $dbstring .= $this->dbtype.':';
        $dbstring .= 'host='.$this->host;
        if($this->createdb == TRUE){
            $dbstring .= ';dbname='.$this->dbname;
        }else{
            $this->sql_createDatabase = "CREATE DATABASE IF NOT EXISTS ".$this->dbname;
        }
    try {
                $conn = new PDO($dbstring,$this->dbUser, $this->userPW);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
            {
            echo "Connection failed: " . $e->getMessage();
            }
        return $conn;
    }
    function sql_exceptions($sql){
        return $sql;
    }
    function checkDBisCreated(){
        $conn = $this->connect();
        $sql_check = 'SHOW DATABASES LIKE "'. $this->dbname.'"';
        $this->createdb = $conn->exec($sql_check); 
        $conn=null;
    }
}
