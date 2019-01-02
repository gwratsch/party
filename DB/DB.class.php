<?php
//include_once 'settings.php';
class DB {
    protected $conn;
    protected $db_check=false;
    public $dbUser;
    public $userPW;
    public $dbname;
    public $dbtype;
    public $hostname;
    public $port;
    public $tableList;
    public $createdb = 'no';
    
    function __construct() {
        $this->dbUser='';
        $this->userPW='';
        $this->dbname='';
        $this->dbtype='';
        $this->hostname = '';
        $this->port = '';
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
        require_once '../config.php';
        $conn='';
        
        $dbstring = '';
        $database = config();
        if($database['db_user']==''){
            $formDBsettings=array();
            foreach ($_POST as $key => $value) {
                $formDBsettings[$key]=$value;
            }
            $database = $formDBsettings;
        }
        $dbstring .= $database['db_type'].':';
        $dbstring .= 'host='.$database['host'];
        if($database['db_type'] == 'mysql'){
            if($this->createdb != 'yes'){$dbstring .= ';dbname='.$this->dbname;}
        }
        if($database['db_type'] == 'pgsql'){
            $dbstring .= ';port='. $this->port;
            $dbstring .= ';user='. $this->dbUser;
            $dbstring .= ';password='. $this->userPW;
            if($this->createdb != 'yes'){$dbstring .= ';dbname='.$this->dbname ;}
        }
    try {
            if($database['db_type'] == 'mysql'){
                $conn = new PDO($dbstring,$this->dbUser, $this->userPW);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db_check=$conn->getAttribute(PDO::ATTR_CONNECTION_STATUS);
            }
            if($database['db_type'] == 'pgsql'){
                $conn = new PDO($dbstring);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db_check=$conn->getAttribute(PDO::ATTR_CONNECTION_STATUS);
            }
            
        }
        catch(PDOException $e)
            {
            echo "Connection failed: " . $e->getMessage();
            }
        return $conn;
    }
    function create(){
        $this->createdb();
        $this->createTables();
    }
    function createdb(){
        try {
            $this->createdb='yes';
            $conn = DB::connect();
            $sql = "CREATE DATABASE IF NOT EXISTS $this->dbname";
            $conn->exec($sql);
            echo "Database created successfully<br>";
            $conn = null;
            $this->createdb='no';
            }
        catch(PDOException $e)
            {
            echo "createdb : ".$sql . "<br>" . $e->getMessage();
            }
    }
    function createTables(){
        $conn = DB::connect();
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
