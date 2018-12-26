<?php
include_once 'modules/translation.php';

function __autoload($class_name) {
    require_once 'includes/'.$class_name . '.class.php';
}

function debuginfo($array){
    var_dump(print_r("<pre>".print_r($_POST,true)."</pre>"));
}