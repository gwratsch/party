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
        $tablelist = (new DB_install)->tablelist($dbinstall->dbtype);
        $dbinstall->checktablename = 'dbconfig';
        if($dbinstall->checktableisCreated()){
            $settings['tablename']='dbconfig';
            $settings['fieldnames']="lastupdate";
            $settings['fieldconditions']='';
            $selectResultArray = $dbinstall->select($settings);
            $dbinstall->checktablename = $selectResultArray[0];
        }
        $dbinstall->tableList = $tablelist;
        $tablelist = (new DB_install)->updateDB($dbinstall->dbtype);
        $dbinstall->updatetableList = $tablelist;
        $dbinstall->create();
    }
    
    function tablelist($dbtype){
        $tableInfo = array();    
        $tableInfo['rebuild_tables'] = false;
        $table_list = array(
            "mysql"=>array(
                "users"=>"CREATE TABLE IF NOT EXISTS users (userid int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,firstname varchar(50) NOT NULL,lastname varchar(50) NOT NULL,adres varchar(100) NOT NULL,city varchar(50) NOT NULL,country varchar(50) NOT NULL,email varchar(200),user_info varchar(255),reg_date timestamp)",
                "userdisplay"=>"CREATE TABLE IF NOT EXISTS userdisplay (udid int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,userid int(6) NOT NULL,fieldname varchar(50) NOT NULL)"
                ),
            "pgsql"=>array(
                "users"=>"CREATE SEQUENCE users_userid_seq; CREATE TABLE IF NOT EXISTS users (userid int(6) NOT NULL PRIMARY KEY DEFAULT nextval('users_userid_seq'),firstname varchar(50) NOT NULL,lastname varchar(50) NOT NULL,adres varchar(100) NOT NULL,city varchar(50) NOT NULL,country varchar(50) NOT NULL,email varchar(200),user_info varchar(255),reg_date timestamp);
                ALTER SEQUENCE users_userid_seq OWNED BY users.id;",
                "userdisplay"=>"CREATE SEQUENCE userdisplay_udid_seq; CREATE TABLE IF NOT EXISTS userdisplay (udid int(6) NOT NULL PRIMARY KEY nextval('userdisplay_udid_seq'),userid int(6) NOT NULL,fieldname varchar(50) NOT NULL);
                ALTER SEQUENCE userdisplay_udid_seq OWNED BY userdisplay.id;"
            )
        );
        $tableInfo['table_list'] = $table_list[$dbtype];
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
    function updateDB($dbtype){
        $table_list = array(
            "mysql"=>array(
                "1"=>"CREATE TABLE IF NOT EXISTS dbconfig (
                id int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, 
                lastupdate int(6) NOT NULL,
                reg_date timestamp
                )",
                "2"=>"INSERT INTO dbconfig (
                lastupdate) VALUES ('0')", 
                "3"=>"CREATE TABLE IF NOT EXISTS party (
                partyid int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, 
                userid int(6) NOT NULL,
                partyinfo varchar(255) NOT NULL,
                location varchar(255) NOT NULL,
                reg_date timestamp
                )",
                "4"=>"CREATE TABLE IF NOT EXISTS wishlist (
                wlid int(6) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY, 
                partyid int(6) NOT NULL,
                userid int(6) NOT NULL,
                wlinfo varchar(255) NOT NULL,
                reg_date timestamp
                )",
                "5"=>"ALTER TABLE party ADD partylist boolean",
                "6"=>"ALTER TABLE users ADD password varchar(255)"
                ),
            "pgsql"=>array(
                "1"=>"CREATE SEQUENCE dbconfig_id_seq;",
                "2"=>"CREATE TABLE IF NOT EXISTS dbconfig ( "
                . "id integer NOT NULL DEFAULT nextval('dbconfig_id_seq'::regclass),"
                . "lastupdate integer NOT NULL, "
                . "reg_date timestamp, "
                . "CONSTRAINT id PRIMARY KEY (id)"
                . ");",
                "3"=>"ALTER SEQUENCE dbconfig_id_seq OWNED BY dbconfig.id;",
                "4"=>"INSERT INTO dbconfig ( lastupdate) VALUES ('0')", 
                "5"=>"CREATE SEQUENCE party_id_seq;",
                "6"=>"CREATE TABLE IF NOT EXISTS party ( partyid integer NOT NULL DEFAULT NEXTVAL('party_id_seq') PRIMARY KEY, userid integer NOT NULL, partyinfo varchar(255) NOT NULL, location varchar(255) NOT NULL, reg_date timestamp );",
                "7"=>"ALTER SEQUENCE party_id_seq OWNED BY party.partyid;",
                "8"=>"CREATE SEQUENCE wishlist_id_seq;",
                "9"=>"CREATE TABLE IF NOT EXISTS wishlist ( wlid integer NOT NULL DEFAULT NEXTVAL('wishlist_id_seq') PRIMARY KEY, partyid integer NOT NULL, userid integer NOT NULL, wlinfo varchar(255) NOT NULL, reg_date timestamp );",
                "10"=>"ALTER SEQUENCE wishlist_id_seq OWNED BY wishlist.wlid;",
                "11"=>"ALTER TABLE party ADD partylist boolean",
                "12"=>"ALTER TABLE users ADD password varchar(255)",
                "13"=>"ALTER TABLE party ADD partylist boolean NOT NULL",
                "14"=>"ALTER TABLE party ALTER COLUMN partylist SET NOT NULL"
            )
        );
        $updateScripts = $table_list[$dbtype];
        return $updateScripts;
    }

}