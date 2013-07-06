<?php

## add specific app data here
$appconf["title"] = "Meetoparty";
$appconf["url"] = 'http://' . $_SERVER['SERVER_NAME'] . '/';
$appconf['memberdir'] = "memberdir/";

## add other config files if use
$dbcon = new config_mysql();
$reg->dbcon = $dbcon->getConnection(); //register it

//load app libs
$reg->user = new libs_user(@$_SESSION["id_member"]); //user data lib

//app constants
define('TARGET', MEMDIR . hash('crc32', crc32(PREFIXE) . @$_SESSION['id_member'] . crc32(SUFFIXE)) . '/');    // Repertoire cible

//set roles -- this can be moved to database to alow roles menagment by admin
$roleList = array(
   // 'Role_Level' => "RoleName",
    '-1' => array("name" => "Deconn", "dContr" => "account", "dAction" => "login" ),
    '0' =>  array("name" => "UnVerif", "dContr" => "account", "dAction" => "login" ),
    '1' =>  array("name" => "UTILISATEUR", "dContr" => "account", "dAction" => "login" ),
    '2' =>  array("name" => "PREMIUM_U", "dContr" => "account", "dAction" => "login" ),
    '3' =>  array("name" => "ENTREPRISE", "dContr" => "account", "dAction" => "login" ),
    '4' =>  array("name" => "PREMIUM_E", "dContr" => "account", "dAction" => "login" ),
    '6' =>  array("name" => "ANIMATEUR", "dContr" => "account", "dAction" => "login" ),
    '7' =>  array("name" => "MODERATEUR", "dContr" => "account", "dAction" => "login" ),
    '8' =>  array("name" => "ADMINISTRATEUR", "dContr" => "account", "dAction" => "login" ),
    '9' =>  array("name" => "FONDATEUR", "dContr" => "account", "dAction" => "login" )  
);
?>