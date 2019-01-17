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
    public $checktablename;
    public $tabelnameCheckResult;
    public $lastUpdateNumber=0;
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
        $sql = "SELECT ".$settings['fieldnames']." FROM ".$settings['tablename'];
        if($settings['fieldconditions'] !=''){$sql .=" WHERE ".$settings['fieldconditions'];}
        $sql .=" ;";
        $result = $conn->prepare($sql);
        $result->execute();
        $resultarray = $result->fetchAll(PDO::FETCH_CLASS);
        $conn=null;
        return $resultarray;
    }
    function update($settings){
        $conn = $this->connect();
        $sql = "UPDATE ".$settings['tablename']." SET ".$settings['fieldvalues'];
        if($settings['fieldconditions'] !=''){$sql .=" WHERE ".$settings['fieldconditions'];}
        $sql .=" ;";
        $result = $conn->exec($sql);
        $conn=null;
    }
    function delete($settings){
        $conn = $this->connect();
        $sql = "DELETE FROM ".$settings['tablename'];
        if($settings['fieldconditions'] !=''){$sql .=" WHERE ".$settings['fieldconditions'];}
        $sql .=" ;";
        $result = $conn->exec($sql);
        $conn=null;
    }
    function insert($settings){
        $this->checktableisCreated();
        $conn = $this->connect();
        $sql = "INSERT INTO ".$settings['tablename']." (".$settings['fieldnames'].") VALUES (".$settings['fieldvalues'].");";
        $conn->exec($sql);
        $newUserId =  $conn->lastInsertId();
        $conn=null;
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
        $this->updateTables();
    }
    function createdatabase(){
        $this->createdb=FALSE;
        $this->checkDBisCreated();
        if($this->createdb==FALSE){
            try {
                    $sql = $this->sql_createDatabase;
                    $conn = $this->connect();
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
        $this->checktableisCreated();
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
    function updateTables(){
        $this->checktablename = 'dbconfig';
        $this->checktableisCreated();
        if($this->tabelnameCheckResult){
            $settings['tablename']='dbconfig';
            $settings['fieldnames']="lastupdate";
            $settings['fieldconditions']='1=1';
            $selectResultArray = $this->select($settings);
            $this->lastUpdateNumber = $selectResultArray[0]->lastupdate;
        }
        $conn = $this->connect();
        foreach ($this->updatetableList as $key => $value) {
            If($key > $this->lastUpdateNumber){
                $PDOerrorInfo='';
                $PDOarrorCode='';
                try{
                    $value = $this->sql_exceptions($value);
                    $conn->exec($value);
                    echo "\nPDOStatement::errorInfo():\n";
                    $PDOerrorInfo = $conn->errorInfo();
                    $PDOarrorCode = $conn->errorCode();
                    print_r($PDOerrorInfo);
                    echo '<br />'."\nPDO::errorCode(): ". $conn->errorCode().'<br />';
                    $errorexecptions=array(
                        '42P07',
                    );
                    if($PDOarrorCode != 0 && !in_array($PDOarrorCode, $errorexecptions) ){throw new Exception("Foutmelding nr : ".$conn->errorCode());}
                    $settings['tablename']='dbconfig';
                    $settings['fieldvalues']="lastupdate='".$key."'";
                    $settings['fieldconditions']='1=1';
                    $this->update($settings);
                    echo 'Update id : '.$key.' is uitgevoerd.<br />';
                    $this->lastUpdateNumber = $key;
                    }
                catch(PDOException $e)
                    {
                    echo "Update met id : ".$key." en sql : ".$value . " kon niet uitgevoerd worden wegens problemen. <br />Melding: <br>" . $e->getMessage();
                    echo "\nPDOStatement::errorInfo():\n";
                    print_r($PDOerrorInfo);
                    echo "\nPDO::errorCode(): ".$PDOarrorCode.'<br />';
                        break;
                    }
            }
        }
        $lastKey = $this->lastUpdateNumber ;
            $settings['tablename']='dbconfig';
            $settings['fieldnames']="lastupdate";
            $settings['fieldconditions']='1=1';
            $selectResultArray = $this->select($settings);
            //var_dump($selectResultArray);
            $this->lastUpdateNumber = $selectResultArray[0]->lastupdate;
        If ($lastKey == $this->lastUpdateNumber){
            echo t("Alle aanwezige updates zijn uitgevoerd.");
        }
    }
    function sql_exceptions($sql){
        return $sql;
    }
    function checkDBisCreated(){}
    function checktableisCreated(){}
}
