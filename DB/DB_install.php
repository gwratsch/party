<?php
include_once 'settings.php';
include_once 'config.php';
class DB_install{
    
    function installdb() {
        $formDBsettings = config();
        if($formDBsettings['dbname'] == ''){
            foreach ($_POST as $key => $value) {
                $formDBsettings[$key]=$value;
            }

            (new DB_install)->updateConfig($formDBsettings);
        }
        $databasetype = $formDBsettings['dbtype'];
        $dbinstall = new $databasetype();
        
        $dbinstall->dbUser=$formDBsettings['dbUser'];
        $dbinstall->userPW=$formDBsettings['userPW'];
        $dbinstall->dbname=$formDBsettings['dbname'];
        $dbinstall->dbtype=$formDBsettings['dbtype'];
        $dbinstall->host=$formDBsettings['host'];
        $dbinstall->port=$formDBsettings['port'];
        $dbinstall->checkDBisCreated();
        $tablelist = (new DB_install)->tablelist();
        $dbinstall->checktablename = 'dbconfig';
        if($dbinstall->checktableisCreated()){
            $settings['tablename']='dbconfig';
            $settings['fieldnames']="lastupdate";
            $settings['fieldconditions']='1=1';
            $selectResultArray = $dbinstall->select($settings);
            $dbinstall->checktablename = $selectResultArray[0];
        }
        $dbinstall->tableList = $tablelist;
        $tablelist = (new DB_install)->updateDB();
        $dbinstall->updatetableList = $tablelist;
        $dbinstall->create();
    }
    
    function tablelist(){
        $tableInfo = array();    
        $tableInfo['rebuild_tables'] = false;
        $tableInfo['table_list'] = array(
            "users"=>"CREATE TABLE IF NOT EXISTS users (userid int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,firstname varchar(50) NOT NULL,lastname varchar(50) NOT NULL,adres varchar(100) NOT NULL,city varchar(50) NOT NULL,country varchar(50) NOT NULL,email varchar(200),user_info varchar(255),reg_date timestamp)",
            "userdisplay"=>"CREATE TABLE IF NOT EXISTS userdisplay (udid int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,userid int(6) NOT NULL,fieldname varchar(50) NOT NULL)"
        );
        return $tableInfo;
    }  

    function updateConfig($formDBsettings){
        $filename = "config.php";
        if(file_exists($filename)){
            $configFile = fopen($filename, "r");
            $filesize = fstat($configFile);
            $content = fread($configFile, $filesize[7]);
            fclose($configFile);
            foreach($formDBsettings as $key=>$value){
                $string = "'".$key."' => ''";
                $replacestring = "'".$key."' => '".$value."'";
                $content = str_replace($string, $replacestring, $content);
            }
            $configFile = fopen($filename, "w");
            $actionMessage = fwrite($configFile, $content);
            if($actionMessage == false){
                echo "Error: writing to config went wrong.";
            }
            fclose($configFile);
        }else{
            echo t('The database settings are not save. This will not work correct.');
        }
    }
    // add Database updates in this functions
    // a new function have to add new updates.
    // save in the database the last executed update key
    function updateDB(){
        $updateScripts= array(
            "1"=>"CREATE TABLE IF NOT EXISTS dbconfig (
        id int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, 
        lastupdate int(6) NOT NULL,
        reg_date timestamp
        )",
            "2"=>"CREATE TABLE IF NOT EXISTS party (
        partyid int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, 
        userid int(6) NOT NULL,
        partyinfo varchar(255) NOT NULL,
        location varchar(255) NOT NULL,
        reg_date timestamp
        )",
            "3"=>"CREATE TABLE IF NOT EXISTS wishlist (
        wlid int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, 
        partyid int(6) NOT NULL,
        userid int(6) NOT NULL,
        wlinfo varchar(255) NOT NULL,
        reg_date timestamp
        )",
            "4"=>"INSERT INTO dbconfig (
        lastupdate) VALUES ('0')", 
            "5"=>"ALTER TABLE party ADD partylist boolean",
            "6"=>"ALTER TABLE users ADD password varchar(255)",
        );
        return $updateScripts;
    }

}