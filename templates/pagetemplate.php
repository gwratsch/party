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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>';
        return $content;
    }
    function header(){
        $content = '<header class="container">
    <h1>'.t($this->title).'</h1>
</header>';
        return $content;
    }
    function navigation(){
        $content='<nav class="navbar navbar-expand-sm bg-dark navbar-dark rounded">
    <a href="user.php">User settings</a>
</nav>';
        return $content;
    }
    function footer(){
        $content='';
        return $content;
    }
}
