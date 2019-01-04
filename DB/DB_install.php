<?php

class DB_install extends  DB{
    
    function installdb() {
        $formDBsettings=array();
        foreach ($_POST as $key => $value) {
            $formDBsettings[$key]=$value;
        }
        self::updateConfig($formDBsettings);
        $dbinstall = new DB();
        $dbinstall->dbUser=$formDBsettings['db_user'];
        $dbinstall->userPW=$formDBsettings['db_pw'];
        $dbinstall->dbname=$formDBsettings['database'];
        $dbinstall->dbtype=$formDBsettings['db_type'];
        $dbinstall->hostname=$formDBsettings['host'];
        $dbinstall->port=$formDBsettings['port'];
        $dbinstall->tableList = self::tablelist();
        $dbinstall->create();
    }
    
    function tablelist(){
        $tableInfo = array();    
        $tableInfo['rebuild_tables'] = false;
        $tableInfo['table_list'] = array(
            "user"=>"CREATE TABLE IF NOT EXISTS user (
            userId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            adres VARCHAR(50) NOT NULL,
            city VARCHAR(30) NOT NULL,
            country VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            user_info VARCHAR(255),
            reg_date TIMESTAMP
            )",
            "userdisplay"=>"CREATE TABLE IF NOT EXISTS userdisplay (
            UDid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            userId INT(6) NOT NULL,
            fieldname VARCHAR(30) NOT NULL
            )"
        );
        return $tableInfo;
    }  

    function updateConfig($formDBsettings){
        $filename = "config.php";
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
        fwrite($configFile, $content);
        fclose($configFile);
    }
    // add Database updates in this functions
    // a new function have to add new updates.
    // save in the database the last executed update key
    function updateDB(){
        $updateScripts= array(
            "1"=>"CREATE TABLE IF NOT EXISTS dbconfig (
        lastupdate int(6) NOT NULL,
        reg_date TIMESTAMP
        )",
            "2"=>"CREATE TABLE IF NOT EXISTS party (
        partyId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        userid int(6) NOT NULL,
        partyInfo VARCHAR(255) NOT NULL,
        location VARCHAR(100) NOT NULL,
        reg_date TIMESTAMP
        )",
            "3"=>"CREATE TABLE IF NOT EXISTS wishlist (
        wlId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        partyId int(6) NOT NULL,
        userid int(6) NOT NULL,
        wlInfo VARCHAR(255) NOT NULL,
        reg_date TIMESTAMP
        )"
        );
        return $updateScripts;
    }

}