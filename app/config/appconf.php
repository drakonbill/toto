<?php
## add specific app data here
$appconf["title"] = "Meetoparty"; 
$appconf["url"] = "http://localhost/";

## add other config files if use
//include_once('mysql.php'); // autoload will do include
$dbcon = new config_mysql(); 
$reg->dbcon = $dbcon->getConnection(); //register it

//load app libs
$reg->user = new libs_user(@$_SESSION["id_member"]); //user data lib

?>