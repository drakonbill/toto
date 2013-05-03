<?php
## add specific app data here
$appconf["title"] = "Meetoparty"; 
$appconf["url"] = 'http://'.$_SERVER['SERVER_NAME'].'/';
$appconf['memberdir'] = "memberdir/";

## add other config files if use
$dbcon = new config_mysql(); 
$reg->dbcon = $dbcon->getConnection(); //register it

//load app libs
$reg->user = new libs_user(@$_SESSION["id_member"]); //user data lib

//app constants
define('TARGET', MEMDIR.hash('crc32',crc32(PREFIXE).@$_SESSION['id_member'].crc32(SUFFIXE)).'/');    // Repertoire cible


?>