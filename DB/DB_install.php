<?php
function install($formDBsettings){
    require 'DB.class.php';
    $dbinstall = new DB();
    $dbinstall->dbUser=$formDBsettings['db_user'];
    $dbinstall->userPW=$formDBsettings['db_pw'];
    $dbinstall->dbname=$formDBsettings['database'];
    $dbinstall->hostname=$formDBsettings['host'];
    $dbinstall->createdb();
    $dbinstall->tableList = tablelist();
    $dbinstall->createTables();
    updateConfig($formDBsettings);
}
function tablelist(){
$tableInfo = array();    
$tableInfo['rebuild_tables'] = false;
$tableInfo['table_list'] = array(
    "user"=>"CREATE TABLE user (
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
    "userdisplay"=>"CREATE TABLE userdisplay (
    UDid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    userId INT(6) NOT NULL,
    fieldname VARCHAR(30) NOT NULL
    )"
);
return $tableInfo;
}
function updateConfig($formDBsettings){
    $filename = "../config.php";
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
        "1"=>"create DB version control table"
    );
    return $updateScripts;
}