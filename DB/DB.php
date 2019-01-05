<?php
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
    public $createdb = 'yes';
    
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
        include_once 'config.php';
        $dbstring = '';
        $database = config();
        if($database['db_user']==''){
            $formDBsettings=array();
            foreach ($_POST as $key => $value) {
                $formDBsettings[$key]=$value;
            }
            $database = $formDBsettings;
        }
        $this->dbname = $database['database'];
        $this->dbtype = $database['db_type'];
        switch ($this->dbtype) {
            case 'mysql':
                return $this->connectToMysql($database);
                break;
            case 'pgsql':
                return $this->connectToPgsql($database);
                break;
            default:
                break;
        }

    }
    function connectToMysql($database){
        $dbstring = '';
        $dbstring .= $database['db_type'].':';
        $dbstring .= 'host='.$database['host'];
        if($this->createdb == 'yes'){
            $dbstring .= ';dbname='.$database['database'];
        }

    try {
            if($database['db_type'] == 'mysql'){
                $conn = new PDO($dbstring,$database['db_user'], $database['db_pw']);
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
    function connectToPgsql($database){
        /** using Heroku connection options to test the connection.
        $dbstring = '';
        $dbstring .= $database['db_type'].':';
        $dbstring .= 'host='.$database['host'];
        $dbstring .= ';port='. $database['port'];
        if($this->createdb == 'yes'){$dbstring .= ';dbname='.$database['database'] ;}
        if($this->createdb == 'no'){$dbstring .= ';dbname= ' ;}
        $dbstring .= ';user='. $database['db_user'];
        $dbstring .= ';password='. $database['db_pw'];
         * 
         */
            $dbst = parse_url(getenv("DATABASE_URL"));

            
    try {
            //$conn = new PDO($dbstring);
                $conn = new PDO("pgsql:" . sprintf(
                    "host=%s;port=%s;user=%s;password=%s;dbname=%s",
                    $dbst["host"],
                    $dbst["port"],
                    $dbst["user"],
                    $dbst["pass"],
                    ltrim($dbst["path"], "/")
                ));
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db_check=$conn->getAttribute(PDO::ATTR_CONNECTION_STATUS);
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
        $sql = "CREATE DATABASE IF NOT EXISTS $this->dbname";
        try {
            $this->createdb='no';
            $conn = DB::connect();
            $conn->exec($sql);
            echo "Database created successfully<br>";
            $conn = null;
            $this->createdb='yes';
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
                if($this->dbtype == 'pgsql'){$value = $this->pgsql_exceptions($value);}
                $conn->exec($value);
                }
            catch(PDOException $e)
                {
                echo $value . "<br>" . $e->getMessage();
                }
        }
    }function pgsql_exceptions($value){
        $value = str_replace('UNSIGNED AUTO_INCREMENT', 'SERAIL', $value);
        $value = str_replace('(6)', '', $value);
        $value = str_replace('(30)', '', $value);
        $value = str_replace('(50)', '', $value);
        $value = str_replace('(255)', '', $value);
        return $value;
    }
}
