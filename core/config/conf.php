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



?>