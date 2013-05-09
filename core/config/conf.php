<?php

## some constants
define("APPDIR", "app/");
define("COREDIR", "core/");

## load some libs
require_once('../' . COREDIR . 'lib/validate.php');
require_once('../' . COREDIR . 'lib/error.php');
require_once('../' . COREDIR . 'lib/urlParse.php');
require_once('../' . COREDIR . 'lib/cleanData.php');
require_once('../' . COREDIR . 'lib/libMySQL.php');
require_once('../' . COREDIR . 'lib/roles.php');

## create site roles
$roles["admin"] = new roles("Admin");
$roles["conn"] = new roles("Conn");
$roles["decon"] = new roles("Decon");

##add to Registry
$reg->roles = $roles;
$reg->clean = new cleanData(); //this lib dont have extra setings, and can be loaded
$reg->error = new error();
$reg->urlParse = new urlParse();
$reg->validate = new validate();

debug("registry  ...filling with libs");
?>