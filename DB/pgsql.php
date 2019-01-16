<?php

class pgsql  extends  DB{
    public $sql_createDatabase = '';
    public $tabelUsers;
    public $tabelUserdisplay;
    public $tableslist;
    
    function connect(){
        parent::connect();
        $this->sql_createDatabase = "CREATE DATABASE ".$this->dbname;
        $dbst = parse_url(getenv("DATABASE_URL"));
    try {
            $conn = new PDO("pgsql:" . sprintf(
                "host=%s;port=%s;user=%s;password=%s;dbname=%s",
                $dbst["host"],
                $dbst["port"],
                $dbst["user"],
                $dbst["pass"],
                ltrim($dbst["path"], "/")
            ));
        }
        catch(PDOException $e)
            {
            echo "Connection failed: " . $e->getMessage();
            }
        return $conn;
    }
    function sql_exceptions($sql){
        $sql = str_replace('UNSIGNED AUTO_INCREMENT', 'serial', $sql);
        return $sql;
    }
    function checkDBisCreated(){
        $this->createdb = FALSE;       
        $conn = $this->connect();
        $sql_check = 'SELECT 1 FROM pg_database WHERE datname="'. $this->dbname.'"';
        $result = $conn->prepare($sql_check);
        $result->execute();
        $resultarray = $result->fetchAll();

        if(is_array($resultarray) && count($resultarray)>0){
            $this->createdb = TRUE; 
        }
        $conn = null;
    }
    function checktableisCreated(){
        $conn = $this->connect();
        $tablename = $this->checktablename;
        $sql_check = 'select column_name, data_type, character_maximum_length
from INFORMATION_SCHEMA.COLUMNS where table_name = "'.$tablename.'"';
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
