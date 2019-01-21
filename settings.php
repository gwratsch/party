<?php
include_once 'modules/translation.php';
session_start();

spl_autoload_register(function ($class_name) {
    $pathlist = array(
        'includes',
        'templates',
        'DB',
        'view'
    );
    foreach($pathlist as $pathname){
        $file = $pathname.'/'.$class_name . '.php';
        if(file_exists($file)){
            require_once $pathname.'/'.$class_name . '.php';
            break;
        }
    }
});
(new login)->logincheck();
function debuginfo($array){
    var_dump(print_r("<pre>".print_r($array,true)."</pre>"));
}
