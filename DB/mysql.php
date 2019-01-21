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
        $this->createdb = FALSE;
        $conn = $this->connect();
        $sql_check = 'SHOW DATABASES LIKE "'. $this->dbname.'"';
        $result = $this->connection->prepare($sql_check);
        $result->execute();
        $resultarray = $result->fetchAll();
        if(is_array($resultarray) && count($resultarray)>0){
            $this->createdb = TRUE; 
        }
        $conn=null;
    }
    function checktableisCreated(){
        $conn = $this->connect();
        $tablename = $this->checktablename;
        $dbname = $this->dbname;
        $sql_check = 'select * from INFORMATION_SCHEMA.TABLES where table_schema = "'.$dbname.'" AND table_name = "'.$tablename.'"';
        $result = $conn->prepare($sql_check);
        $result->execute();
        $resultarray = $result->fetchAll();
        $this->tabelnameCheckResult = FALSE;
        if(is_array($resultarray) && count($resultarray)>0){
            $this->tabelnameCheckResult  = TRUE; 
        }
        $conn = null;
    }
}
