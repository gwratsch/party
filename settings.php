<?php
include_once 'modules/translation.php';

//$path = configsettings::pathname();
function __autoload($class_name) {
    $pathlist = array(
        'includes',
        'templates',
        'DB'
    );
    foreach($pathlist as $pathname){
        $file = $pathname.'/'.$class_name . '.php';
        if(file_exists($file)){
            include_once $pathname.'/'.$class_name . '.php';
            break;
        }
    }
}

function debuginfo($array){
    var_dump(print_r("<pre>".print_r($array,true)."</pre>"));
}
