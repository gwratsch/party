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
        $conn = $this->connect();
        $sql_check = 'SELECT 1 FROM pg_database WHERE datname="'. $this->dbname.'"';
        $this->createdb = $conn->exec($sql_check); 
        $conn = null;
    }
    function checktableisCreated(){
        $conn = $this->connect();
        $sql_check = 'select column_name, data_type, character_maximum_length
from INFORMATION_SCHEMA.COLUMNS where table_name = "users"';
        $this->tabelUsers = $conn->exec($sql_check); 
        $sql_check = 'select column_name, data_type, character_maximum_length
from INFORMATION_SCHEMA.COLUMNS where table_name = "userdisplay"';
        $this->tabelUserdisplay = $conn->exec($sql_check);
        $conn = null;
    }

}
