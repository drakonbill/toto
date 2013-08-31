<?php

## some constants
define("APPDIR", "app/");
define("COREDIR", "core/");

## config libs loader
$core_libs = array("coreInfo","validate", "error", "cleanData");

//optional libs
require_once('../' . COREDIR . 'lib/libMySQL.php');
require_once('../' . COREDIR . 'lib/user.php');
require_once('../' . COREDIR . 'lib/access.php');

//ACL Levels

final class LEVELS {

    private function __construct() {}
    
    const FORBIDEN = 0;
    const GET = 1;
    const ADD = 2;
    const CHANGE = 3;
    const DELETE = 4;
    //default level, it is used if no level set
    const DEF = LEVELS::FORBIDEN;

}

//API folders
$reg->api = array("api");
?>