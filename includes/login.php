<?php

class login {
    const USERTABLENAME = 'users';

    public function userlogin(){
        if (isset($_POST["login"]) && !empty($_POST["email"]) 
           && !empty($_POST["password"])) {
            $email = $_POST["email"];
            $loginOk = TRUE;
            $userInfo = self::select($email);
            var_dump($userInfo);
            var_dump($this->hashPwd($_POST["password"]));
            if(!isset($userInfo) || !array_key_exists(0,$userInfo)){$loginOk = FALSE;}
            if(array_key_exists(0, $userInfo) && $_POST["email"] != $userInfo[0]->email){$loginOk = FALSE;}
            if(array_key_exists(0, $userInfo) && $this->hashPwd($_POST["password"]) != $userInfo[0]->password){$loginOk = FALSE;}
           if ($loginOk == TRUE) {
              $_SESSION["valid"] = TRUE;
              $_SESSION["timeout"] = time();
              $_SESSION["email"] = $userInfo[0]->email;
              $_SESSION["userid"] = $userInfo[0]->userid;
              $msg = "You have entered valid use name and password";
           }else {
              $msg = "Wrong username or password";
              $this->cleansession();
           }
        }
        return $msg;
    }
    public function hashPwd($password){
        $newPaswrd = hash("sha256",$password);
        return $newPaswrd;
    }
    function dbconnection(){
        $this->dbtype = $dbtype = (new DB)->databasetype();
        $this->DBconnect = new $dbtype();
    }
    public function select($email){
        $this->dbconnection();
        $sqlsettings=array();
        $sqlsettings['tablename']= self::USERTABLENAME;
        $sqlsettings['fieldnames'] = 'userid, email, password';
        $valuesconditions = "email = '".$email."'";
        $sqlsettings['fieldconditions'] = $valuesconditions;
        return $this->DBconnect->select($sqlsettings);
    }
    private function globalAccessPages(){
        $list = array(
        '/user.php',
        '/login.php',
        '/update.php',
         '/install.php',
         '/index.php'
        );
        return $list;
    }
    public function logincheck(){
        $pagename = $_SERVER['PHP_SELF'];
        $pagelist = $this->globalAccessPages();
        $isLogedIn = FALSE;
        if(array_key_exists('userid', $_SESSION)){$isLogedIn = TRUE;}
        if (!in_array($pagename, $pagelist) && $isLogedIn == FALSE){
            $this->cleansession();
            header("location: user.php");
        }
    }
    protected function cleansession(){
        if(array_key_exists("valid", $_SESSION)){unset($_SESSION["valid"]);}
        if(array_key_exists("timeout", $_SESSION)){unset($_SESSION["timeout"]);}
        if(array_key_exists("email", $_SESSION)){unset($_SESSION["email"]);}
        if(array_key_exists("userid", $_SESSION)){unset($_SESSION["userid"]);}
    }
}
