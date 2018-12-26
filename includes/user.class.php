<?php
include_once 'DB/DB.class.php';
include_once 'settings.php';

class user {
    protected $userTablename="user";
    protected $userDisplayTablename="userdisplay";
    protected $jsonFieldList="";
    protected $userDisplayInfo=array();
    
    public function __construct(){
     $this->userid=array(
         "content"=>"0",
         "type"=>"number"
     ); 
     $this->firstname=array(
         "content"=>"",
         "type"=>"text",
         "displayInfo"=>""
     );
     $this->lastname=array(
         "content"=>"",
         "type"=>"text",
         "displayInfo"=>""
         );
     $this->adres=array(
         "content"=>"",
         "type"=>"text",
         "displayInfo"=>""
         );
     $this->city=array(
         "content"=>"",
         "type"=>"text",
         "displayInfo"=>""
         );
     $this->country=array(
         "content"=>"",
         "type"=>"text",
         "displayInfo"=>""
         );
     $this->email=array(
         "content"=>"",
         "type"=>"email",
         "displayInfo"=>""
         );
     $this->user_info=array(
         "content"=>"",
         "type"=>"text",
         "displayInfo"=>""
         );
    }
    public function save(){
        //$tablename="user";
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
        }
        $userid = $this->userid;
        //debuginfo($_POST);
        //debuginfo($this);
        if($userid['content']==0){
            $userid['content'] = $this->insert();
            $this->userid = $userid;
        }else{
            $this->update();
        
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
            if(is_array($userDisplayInfoarray) && array_key_exists("UDid", $userDisplayInfoarray)){
                $this->updatedisplay();
            }else{
                $this->insertdisplay();
            }

    }
    private function select(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldnames']="userid, firstname, lastname,adres,city, country, email, user_info ";
        $userContent = $this->userid;
        $userid = $userContent['content'];
        if($userid==0){$userid="*";}
        $valuesconditions = "userId = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        
        return DB::select($sqlsettings);;
    }
    private function insert(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldnames']="firstname, lastname,adres,city, country, email, user_info ";
        $sqlsettings['fieldvalues'] = $this->fieldvalues();
        return DB::insert($sqlsettings);
    }
    private function update(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldvalues'] = $this->fieldvalues();
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userId = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            DB::update($userid,$sqlsettings);
        }
    }
        private function delete(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userId = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            DB::delecte($sqlsettings);
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
    private function selectdisplay(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userDisplayTablename;
        $sqlsettings['fieldnames']="UDid, userid, fieldname";
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userId = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        
        return DB::select($sqlsettings);;
    }
    private function insertdisplay(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userDisplayTablename;
        $sqlsettings['fieldnames']="userid, fieldname";
        $sqlsettings['fieldvalues'] = $this->displayfieldvaluesInsert();

        return DB::insert($sqlsettings);
    }
    private function updatedisplay(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userDisplayTablename;
        $sqlsettings['fieldvalues'] = $this->displayfieldvaluesUpdate();
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userId = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            DB::update($sqlsettings);
        }
    }
    private function deletedisplay(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userDisplayTablename;
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "userId = ".$userid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            DB::delecte($sqlsettings);
        }
    }
    private function displayfieldvaluesInsert(){
        $values='';
        $userid = $this->userid;
        $values .= "'".$userid['content']."','".$this->jsonFieldList."'";
        return $values;
    }
    private function displayfieldvaluesUpdate(){
        $counter=0;
        $values='';
        foreach($this->userDisplayInfo as $key=>$value){
                if($counter==0){
                    $values .= $key."='".$value."'"; 
                    $counter += 1;
                }else{
                    $values .= ", ".$key."='".$value."'"; 
                }
        }
        return $values;
    }
}
