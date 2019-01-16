<?php
include_once 'settings.php';
class pagetemplate {
    public $title='';
    function __construct() {
        $this->title='';
    }
    function head(){
        $content = '<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/party.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<link rel="icon" href="/site/image/favicon.png" sizes="32x32">'.$this->backgroundImage();
        return $content;
    }
    function header(){
        $content = '<header class="container p-3">
    <h1>'.t($this->title).'</h1>
</header>';
        return $content;
    }
    function navigation(){
        $displayLogin = '<a class="nav-item pr-4 float-right" href="login.php">Login</a>';
        if(isset($_SESSION) && array_key_exists('valid', $_SESSION) && $_SESSION["valid"] == true){
            $displayLogin = '<a class="nav-item pr-4" href="party.php">Party beheer</a>'
                    . '<a class="nav-item pr-4  float-right" href="logout.php">Logout</a>';
        }
        $content='<nav class="navbar navbar-expand-sm bg-dark navbar-dark rounded  mb-2 mt-2 ">
            <a class="nav-item pr-4" href="user.php">User aanmaken</a>
            '.$displayLogin.'
</nav>';
        return $content;
    }
    function footer(){
        $content='';
        return $content;
    }
    function backgroundImage(){
        $number = rand(1,9);
        $scripttext = "<style>body{ background: url('/site/image/image".$number.".jpg') no-repeat; background-size: cover}</style>";
        return $scripttext;
    }
}
