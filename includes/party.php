<?php
    
class party {
    public $userTablename="party";
    private $DBclass;
    public $PageClassFile = "party.php"; 
    
    function __construct() {
        $this->partyid=array(
             "name"=>"partyId",
             "content"=>"0",
             "type"=>"hidden",
             "defaultChecked"=>"",
             "displayInfo"=>"",
             "primary_key"=>TRUE
         );
        $this->userid=array(
             "name"=>"userid",
             "content"=>"0",
             "type"=>"hidden",
             "defaultChecked"=>"",
             "displayInfo"=>"",
             "foreign_key"=>TRUE
         );
       $this->partyinfo=array(
             "name"=>"partyInfo",
             "content"=>"",
             "type"=>"textarea",
             "defaultChecked"=>"",
             "displayInfo"=>""
         );
        $this->location=array(
             "name"=>"location",
             "content"=>"",
             "type"=>"textarea",
             "defaultChecked"=>"",
             "displayInfo"=>""
         );
        $this->partylist=array(
             "name"=>"partylist",
             "content"=>"false",
             "type"=>"checkbox",
             "defaultChecked"=>"",
             "displayInfo"=>""
         );
         $this->dbconnection();
    }
    function dbconnection(){
        $dbtype = (new DB)->databasetype();
        $this->DBclass = new $dbtype();
    }
    function save(){
        $security = new security();
        $result = $_POST; 
        foreach($result as $fieldName => $fieldValue){
            if(array_key_exists($fieldName, $this)){
                $field = $this->$fieldName;
                $type = $field['type'];
                $contentCheck = $security->valid($fieldValue,$type);
                $field["content"]= $contentCheck;
                $this->$fieldName = $field;
            }
            if($fieldName == 'userid'){
                $this->userid['content']=intval($fieldValue);
            }
            if($fieldName == 'partyid'){
                $this->partyid['content']=intval($fieldValue);
            }
        }
        $partyid = $this->partyid;
        if($partyid['content']==0){
            $this->insert();
            $this->partyid = $partyid;
        }else{
            $this->update();
        
        }
    }
    function select(){
        $partyId = $this->partyid['content'];
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldnames']=$this->fieldnames('select');
        $valuesconditions ='';
        $connectconditions='';
        $userid=$this->userid['content'];
        $partyid=$this->partyid['content'];
        if($userid > 0){$valuesconditions .= "userid = ".$userid;}
        if(strlen($valuesconditions)>0){
            $connectconditions=" AND ";
        }
        if($partyid>0){$valuesconditions .= $connectconditions."partyid = ".$partyid;}
        if($partyid=='*'){$valuesconditions="";}
        $sqlsettings['fieldconditions'] = $valuesconditions;
        return $this->DBclass->select($sqlsettings);
    }
    function insert(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldnames']= $this->fieldnames('insert');
        $sqlsettings['fieldvalues'] = $this->fieldvalues();
        return $this->DBclass->insert($sqlsettings);
    }
    function update(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $sqlsettings['fieldvalues'] = $this->fieldupdatevalues();
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "partyid = ".$this->partyid['content'];
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            $this->DBclass->update($sqlsettings);
        }
    }
    function delete(){
        $sqlsettings=array();
        $sqlsettings['tablename']= $this->userTablename;
        $userContent = $this->userid;
        $userid = $userContent['content'];
        $valuesconditions = "partyid = ".$partyid;
        $sqlsettings['fieldconditions'] = $valuesconditions;
        if($userid>0){
            $this->DBclass->delecte($sqlsettings);
        }
    }
    private function fieldvalues(){
        $counter=0;
        $values='';
        foreach($this as $key=>$value){
            If(is_array($value) && $key !='partyid' && array_key_exists('content', $value)){
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
                if($queryname == 'insert' && $key=='partyid'){
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
            If(is_array($value) && $key !='partyid' && array_key_exists('content', $value)){
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
    function countcheck_wishlists(){}
    function edit(){
        $result = $this->select();
        foreach ($result[0] as $key => $value) {
            if(array_key_exists($key, $this)){
                $this->$key['content']= $value;
            }
        }
    }
}