<?php
include_once 'modules/translation.php';
class DB {
    protected $conn;
    protected $db_check=true;
    protected $dbUser;
    protected $userPW;
    protected $dbname;
    protected $hostname;
    protected $tableList;
    
    function __construct() {
        $this->dbUser='';
        $this->userPW='';
        $this->dbname='';
        $this->hostname = '';
        $this->tableList=array();
    }
    function select($settings){
        $conn = DB::connect();
        $sql = "SELECT ".$settings['fieldnames']." FROM ".$settings['tablename']." WHERE ".$settings['fieldconditions']." ;";
        $result = $conn->exec($sql);
        $conn=null;
        return $result;
    }
    function update($settings){
        $conn = DB::connect();
        $sql = "UPDATE ".$settings['tablename']." SET ".$settings['fieldvalues']." WHERE ".$settings['fieldconditions']." ;";
        $result = $conn->exec($sql);
        $conn=null;
    }
    function delete($settings){
        $conn = DB::connect();
        $sql = "DELETE FROM ".$settings['tablename']." WHERE ".$settings['fieldconditions']." ;";
        $result = $conn->exec($sql);
        $conn=null;
    }
    function insert($settings){
        $conn = DB::connect();
        $sql = "INSERT INTO ".$settings['tablename']." (".$settings['fieldnames'].") VALUES (".$settings['fieldvalues'].");";
        $conn->exec($sql);
        $newUserId =  $conn->lastInsertId();
        $conn=null;
        return $newUserId;
    }
    
    private function connect(){
        include_once 'DB_install.php';
        include_once 'config.php';
        $conn='';
        $db_check=true;
        $database = config();
        $this->dbname = $database['database'];
        $this->dbUser = $database['db_user'];
        $this->userPW = $database['db_pw'];
        $this->hostname = $database['host'];
    try {
        $conn = new PDO("mysql:host=$this->hostname;dbname=$this->dbname", $this->dbUser, $this->userPW);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo t("Connected successfully"); 
        $this->conn = $conn;
    }
    catch(PDOException $e)
        {
        echo t("Connection failed: ") . $e->getMessage();
        }
    return $conn;
    }
    function createdb(){
        try {
            $conn = new PDO("mysql:host=$this->hostname", $this->dbUser, $this->userPW);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS $this->dbname";
            $conn->exec($sql);
            echo t("Database created successfully<br>");
            $conn = null;
            }
        catch(PDOException $e)
            {
            echo t("createdb : ").$sql . "<br>" . $e->getMessage();
            }
    }
    function createTables(){
        $conn = $this->connect();
        foreach ($this->tableList['table_list'] as $key => $value) {
            try{
                $conn->exec($value);
                }
            catch(PDOException $e)
                {
                echo $value . "<br>" . $e->getMessage();
                }
        }
    }
}
