<?php
include_once 'config.php';
class DB {
    protected $conn;
    protected $db_check=false;
    public $dbUser;
    public $userPW;
    public $dbname;
    public $dbtype;
    public $host;
    public $port;
    public $tableList;
    public $createdb = true;
    protected $databaseType;
    
    function __construct() {
        $this->dbUser='';
        $this->userPW='';
        $this->dbname='';
        $this->dbtype='';
        $this->host = '';
        $this->port = '';
        $this->tableList=array();
    }
    function databasetype(){
        $database = config();
        return $database['dbtype'];
    }
    function select($settings){
        $conn = $this->connect();
        $sql = "SELECT ".$settings['fieldnames']." FROM ".$settings['tablename']." WHERE ".$settings['fieldconditions']." ;";
        $result = $conn->prepare($sql);
        $result->execute();
        $resultarray = $result->fetchAll();
        $conn=null;
        var_dump('DB select: ');
        var_dump($resultarray);
        return $resultarray;
    }
    function update($settings){
        $conn = $this->connect();
        $sql = "UPDATE ".$settings['tablename']." SET ".$settings['fieldvalues']." WHERE ".$settings['fieldconditions']." ;";
        $result = $conn->exec($sql);
        $conn=null;
    }
    function delete($settings){
        $conn = $this->connect();
        $sql = "DELETE FROM ".$settings['tablename']." WHERE ".$settings['fieldconditions']." ;";
        $result = $conn->exec($sql);
        $conn=null;
    }
    function insert($settings){
        $conn = $this->connect();
        $sql = "INSERT INTO ".$settings['tablename']." (".$settings['fieldnames'].") VALUES (".$settings['fieldvalues'].");";
        $conn->exec($sql);
        $newUserId =  $conn->lastInsertId();
        $conn=null;
        var_dump('DB insert created id : ');
        var_dump($newUserId);
        return $newUserId;
    }
    
    public function connect(){
        $dbstring = '';
        $database = config();
        if($database['dbUser']==''){
            $formDBsettings=array();
            foreach ($_POST as $key => $value) {
                $formDBsettings[$key]=$value;
            }
            $database = $formDBsettings;
        }
        $this->dbUser=$database['dbUser'];
        $this->userPW=$database['userPW'];
        $this->host =$database['host'];
        $this->port =$database['port'];
        $this->dbname = $database['dbname'];
        $this->dbtype = $database['dbtype'];

    }
 
    function create(){
        $this->createdatabase();
        $this->createTables();
    }
    function createdatabase(){
        $this->createdb=FALSE;
        $this->checkDBisCreated();
        $sql = $this->sql_createDatabase;
        $conn = $this->connect();
        if($this->createdb==FALSE){
            try {
                    $conn->exec($sql);
                    echo "Database created successfully<br>";
                    $conn = null;
                    $this->createdb=TRUE;
                }
            catch(PDOException $e)
                {
                    echo "createdb : ".$sql . "<br>" . $e->getMessage();
                }
        }
    }
    function createTables(){
        $conn = $this->connect();
        foreach ($this->tableList['table_list'] as $key => $value) {
            try{
                $value = $this->sql_exceptions($value);
                $conn->exec($value);
                }
            catch(PDOException $e)
                {
                echo $value . "<br>" . $e->getMessage();
                }
        }
    }
    function sql_exceptions($sql){
        return $sql;
    }
    function checkDBisCreated(){}
}
