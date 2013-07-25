<?php

## some constants
define("APPDIR", "app/");
define("COREDIR", "core/");

## load some libs
require_once('../' . COREDIR . 'lib/coreInfo.php');
require_once('../' . COREDIR . 'lib/validate.php');
require_once('../' . COREDIR . 'lib/error.php');
require_once('../' . COREDIR . 'lib/urlParse.php');
require_once('../' . COREDIR . 'lib/cleanData.php');
require_once('../' . COREDIR . 'lib/libMySQL.php');
require_once('../' . COREDIR . 'lib/roles.php');
require_once('../' . COREDIR . 'lib/user.php');

## create startup site roles, rewrited in appconf
$roleList = array(
    // 'Role_Level' => "RoleName",

    '0' => array("name" => "Guest", "dContr" => "account", "dAction" => "login"),
    '1' => array("name" => "Member", "dContr" => "account", "dAction" => "login")
);

## add to Registry
$reg->clean = new cleanData();
$reg->error = new error();
$reg->urlParse = new urlParse();
$reg->validate = new validate();

debug("registry  ...filling with libs");
?>