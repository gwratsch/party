<?php
class configsettings {
    static $path;
    static function pathname(){
        self::$path = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].'/';
        return  self::$path;
    }
}
