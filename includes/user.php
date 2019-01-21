<?php
include_once 'settings.php';

class user {
    protected $userTablename="users";
    protected $userDisplayTablename="userdisplay";
    protected $jsonFieldList="";
    protected $userDisplayInfo=array();
    private $DBclass;
    protected $dbtype;
    public $displayProtectInfo=TRUE;
    public $PageClassFile = "user.php"; 
    public $updateInfo = FALSE;
    
    public function __construct(){
     $this->userid=array(
         "name"=>"userid",
         "content"=>"0",
         "defaultChecked"=>"",
         "type"=>"hidden"
         
     ); 
     $this->firstname=array(
         "name"=>"firstname",
         "content"=>"",
         "type"=>"text",
         "defaultChecked"=>"",
         "displayInfo"=>""
     );
     $this->lastname=array(
         "name"=>"lastname",
         "content"=>"",
         "type"=>"text",
         "defaultChecked"=>"",
         "displayInfo"=>""
         );
     $this->adres=array(
         "name"=>"adres",
         "content"=>"",
         "type"=>"text",
         "defaultChecked"=>"checked",
         "displayInfo"=>""
         );
     $this->city=array(
         "name"=>"city",
         "content"=>"",
         "type"=>"text",
         "defaultChecked"=>"checked",
         "displayInfo"=>""
         );
     $this->country=array(
         "name"=>"country",
         "content"=>"",
         "type"=>"text",
         "defaultChecked"=>"checked",
         "displayInfo"=>""
         );
     $this->email=array(
         "name"=>"email",
         "content"=>"",
         "type"=>"email",
         "defaultChecked"=>"checked",
         "displayInfo"=>""
         );
     $this->user_info=array(
         "name"=>"user_info",
         "content"=>"",
         "type"=>"textarea",
         "defaultChecked"=>"checked",
         "displayInfo"=>""
         );
     $this->password=array(
         "name"=>"password",
         "content"=>"",
         "type"=>"password",
         "defaultChecked"=>"checked required",
         "displayInfo"=>""
         );
         $this->dbconnection();
    }
    function dbconnection(){
        $dbtype = (new DB)->databasetype();
        $this->DBclass = new $dbtype();
    }
    public function save(){
        $security = new security();
        $result = $_POST; 
        foreach($result as $fieldName => $fieldValue){
            if(array_key_exists($fieldName, $this)){
                $field = $this->$fieldName;
                $type = $field['type'];
                $contentCheck = $security->valid($fieldValue,$type);
                $field["content"]= $contentCheck;
                if(array_key_exists($fieldName."block", $_POST)){
                    $field["displayInfo"]= $_POST[$fieldName."block"];
                }else{
                    $field["displayInfo"]= "";
                }
                $this->$fieldName = $field;
            }
            if($fieldName == 'userid'){
                $this->userid['content']=$fieldValue;
            }
        }
        $userid = $this->userid;
        if($userid['content']==0){
            $this->password['content']= (new login)->hashPwd($this->password['content']);
            $userid['content'] = $this->insert();
            $this->userid = $userid;
            $this->password['content']= '';
        }else{
            $this->password['content']= (new login)->hashPwd($this->password['content']);
            $this->update();
            $this->password['content']= '';
        }
        $fieldList= array();
        foreach ($this as $keyname => $keycontent) {
            $keyarray = $keycontent;
            if(is_array($keyarray) && array_key_exists('displayInfo', $keyarray)){
                if($keyarray['displayInfo'] == "on"){$fieldList[]=$keyname;}
            }
        }            
            $this->jsonFieldList = json_encode($fieldList);
            $this->userDisplayInfo = $this->selectdisplay();
            $userDisplayInfoarray = $this->userDisplayInfo;
            if(array_key_exists("0", $userDisplayInfoarray) && array_key_exists("udid", $userDisplayInfoarray[0])){
                $this->updatedisplay();
            }else{
                $this->insertdisplay();
            }

    }
    public function select(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldnames'] = $this->fieldnames('select');
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userid = ".$userid;
        if($userid=='*'){$valuesconditions="";}
        $sqlsettings['fieldconditions'] = $valuesconditions;
        return $this->DBclass->select($sqlsettings);
    }
    public function insert(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldnames']= $this->fieldnames('insert');
        $sqlsettings['fieldvalues'] = $this->fieldvalues();
        return $this->DBclass->insert($sqlsettings);
    }
    public function update(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldvalues'] = $this->fieldupdatevalues();
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userid = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            $this->DBclass->update($sqlsettings);
        }
    }
    public function delete(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userid = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            $this->DBclass->delecte($sqlsettings);
        }
    }
    private function fieldvalues(){
        $counter=0;
        $values='';
        foreach($this as $key=>$value){
            If(is_array($value) && $key !='userid' && array_key_exists('content', $value)){
                if($counter==0){
                    $values .= "'".$value['content']."'"; 
                    $counter += 1;
                }else{
                    $values .= ",'".$value['content']."'"; 
                }
            }
        }
        return $values;
    }
    private function fieldnames($queryname){
        $counter=0;
        $values='';
        
        foreach($this as $key=>$value){
            If(is_array($value) && array_key_exists('content', $value)){
                $addValue = TRUE;
                if($queryname == 'insert' && $key=='userid'){
                    $addValue = FALSE;
                }
                if($addValue){
                    if($counter==0 ){
                        $values .= $key; 
                        $counter += 1;
                    }else{
                        $values .= ",".$key; 
                    }
                }
            }
        }
        return $values;
    }
    private function fieldupdatevalues(){
        $counter=0;
        $values='';
        foreach($this as $key=>$value){
            If(is_array($value) && $key !='userid' && array_key_exists('content', $value)){
                if($counter==0){
                    $values .= $key."='".$value['content']."'"; 
                    $counter += 1;
                }else{
                    $values .= ",".$key."='".$value['content']."'"; 
                }
            }
        }
        return $values;
    }
    private function selectdisplay(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userDisplayTablename;
        $sqlsettings['fieldnames']="udid, userid, fieldname";
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userid = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        
        return $this->DBclass->select($sqlsettings);;
    }
    private function insertdisplay(){

        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userDisplayTablename;
        $sqlsettings['fieldnames']="userid, fieldname";
        $sqlsettings['fieldvalues'] = $this->displayfieldvaluesInsert();

        return $this->DBclass->insert($sqlsettings);
    }
    private function updatedisplay(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userDisplayTablename;
        $sqlsettings['fieldvalues'] = $this->displayfieldvaluesUpdate();
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userid = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            $this->DBclass->update($sqlsettings);
        }
    }
    private function deletedisplay(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userDisplayTablename;
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userid = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            $this->DBclass->delecte($sqlsettings);
        }
    }
    private function displayfieldvaluesInsert(){
        $values='';
        $userid = $this->userid;
        $values .= "'".$userid['content']."','".$this->jsonFieldList."'";
        return $values;
    }
    private function displayfieldvaluesUpdate(){
        $values = "fieldname='".$this->jsonFieldList."'";
        return $values;
    }
    function edit(){
        
        $result= $this->select();
        foreach ($result[0] as $key => $value) {
            if(array_key_exists($key, $this) && $key !='password'){
                $this->$key['content']=$value;
            }
        }
    }
}
