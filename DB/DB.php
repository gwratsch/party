<?php
include_once 'config.php';
class DB {
    protected $db_check=false;
    protected $dbUser;
    protected $userPW;
    protected $dbname;
    protected $dbtype;
    protected $host;
    protected $port;
    public $tableList;
    public $createdb = true;
    public $checktablename;
    public $tabelnameCheckResult;
    public $lastUpdateNumber=0;
    protected $databaseType;
    public $connection;
    
    function __construct() {
        $this->dbUser='';
        $this->userPW='';
        $this->dbname='';
        $this->dbtype='';
        $this->host = '';
        $this->port = '';
        $this->tableList=array();
        $this->connection= $this->connect();
    }
    function __destruct() {
        $this->connection= null;
    }
    function databasetype(){
        $database = config();
        return $database['dbtype'];
    }
    function select($settings){
        $resultarray ='';
        $sql = "SELECT ".$settings['fieldnames']." FROM ".$settings['tablename'];
        if($settings['fieldconditions'] !=''){$sql .=" WHERE ".$settings['fieldconditions'];}
        $sql .=" ;";
        try{
            $result = $this->connection->prepare($sql);
            $result->execute();
            $PDOerrorCode = $result->errorCode();
            if($PDOerrorCode !=0){throw new Exception("Foutmelding nr : ".$PDOerrorCode.'. SQLS: '.$sql);}
            $resultarray = $result->fetchAll(PDO::FETCH_CLASS);
        }
        catch (PDOException $e ){
            echo "De sql : ".$sql . " kon niet uitgevoerd worden wegens problemen. <br />Melding: <br>" . $e->getMessage();
        }
        
        return $resultarray;

    }
    function update($settings){
        $sql = "UPDATE ".$settings['tablename']." SET ".$settings['fieldvalues'];
        if($settings['fieldconditions'] !=''){$sql .=" WHERE ".$settings['fieldconditions'];}
        $sql .=" ;";
        try{
            $result = $this->connection->prepare($sql);
            $result->execute();
            $PDOerrorCode = $this->connection->errorCode();
            if($PDOerrorCode !=0){throw new Exception("Foutmelding nr : ".$PDOerrorCode.'. SQLS: '.$sql);}
        }
        catch(PDOException $e){
           echo "De sql : ".$sql . " kon niet uitgevoerd worden wegens problemen. <br />Melding: <br>" . $e->getMessage();
        }
    }
    function delete($settings){
        $sql = "DELETE FROM ".$settings['tablename'];
        if($settings['fieldconditions'] !=''){$sql .=" WHERE ".$settings['fieldconditions'];}
        $sql .=" ;";
        try{
            $result = $this->connection->prepare($sql);
            $result->execute();
            $PDOerrorCode = $this->connection->errorCode();
            if($PDOerrorCode !=0){throw new Exception("Foutmelding nr : ".$PDOerrorCode.'. SQLS: '.$sql);}
        }
        catch(PDOException $e){
           echo "De sql : ".$sql . " kon niet uitgevoerd worden wegens problemen. <br />Melding: <br>" . $e->getMessage();
        }
    }
    function insert($settings){
        $this->checktableisCreated();
        $newUserId='';
        $sql = "INSERT INTO ".$settings['tablename']." (".$settings['fieldnames'].") VALUES (".$settings['fieldvalues'].");";
        try{
            $result = $this->connection->prepare($sql);
            $result->execute();
            $PDOerrorCode = $this->connection->errorCode();
            if($PDOerrorCode !=0){throw new Exception("Foutmelding nr : ".$PDOerrorCode.'. SQLS: '.$sql);}
            $newUserId =  $this->connection->lastInsertId();
        }
        catch(PDOException $e){
           echo "De sql : ".$sql . " kon niet uitgevoerd worden wegens problemen. <br />Melding: <br>" . $e->getMessage();
        }
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
                    $this->connection->exec($sql);
                    echo "Database created successfully<br>";
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
        foreach ($this->tableList['table_list'] as $key => $value) {
            try{
                $value = $this->sql_exceptions($value);
                $this->connection->exec($value);
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
        foreach ($this->updatetableList as $key => $value) {
            If($key > $this->lastUpdateNumber){
                $PDOerrorInfo='';
                $PDOerrorCode='';
                try{
                    $value = $this->sql_exceptions($value);
                    $this->connection->exec($value);
                    $PDOerrorInfo = $this->connection->errorInfo();
                    $PDOerrorCode = $this->connection->errorCode();
                    $errorexecptions=array(
                        '42P07',
                        '42701'
                    );
                    if($PDOerrorCode != 0 && !in_array($PDOerrorCode, $errorexecptions) ){throw new Exception("Foutmelding nr : ".$PDOerrorCode);}
                    $settings['tablename']='dbconfig';
                    $settings['fieldvalues']="lastupdate='".$key."'";
                    $settings['fieldconditions']='id=1';
                    $this->update($settings);
                    echo 'Update id : '.$key.' is uitgevoerd.<br />';
                    $this->lastUpdateNumber = $key;
                    }
                catch(PDOException $e)
                    {
                    echo "Update met id : ".$key." en sql : ".$value . " kon niet uitgevoerd worden wegens problemen. <br />Melding: <br>" . $e->getMessage();
                    echo "\nPDOStatement::errorInfo():\n";
                    print_r($PDOerrorInfo);
                    echo "\nPDO::errorCode(): ".$PDOerrorCode.'<br />';
                        break;
                    }
            }
        }
        $lastKey = $this->lastUpdateNumber ;
            $settings['tablename']='dbconfig';
            $settings['fieldnames']="lastupdate";
            $settings['fieldconditions']='id=1';
            $selectResultArray = $this->select($settings);
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
