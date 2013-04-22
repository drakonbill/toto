<?php
## some constants
define("APPDIR" , "app/");
define("COREDIR" , "core/");

## load some libs
require_once('../'.COREDIR . 'lib/validate.php');
require_once('../'.COREDIR . 'lib/error.php');
require_once('../'.COREDIR . 'lib/urlParse.php');
require_once('../'.COREDIR . 'lib/cleanData.php');
require_once('../'.COREDIR . 'lib/libMySQL.php');

##add to Registry
$reg->clean = new cleanData(); //this lib dont have extra setings, and can be loaded
$reg->error = new error();
$reg->urlParse = new urlParse();
$reg->validate = new validate();

debug("registry  ...filling with libs");



?>