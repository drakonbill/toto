<?php

## start the sessions
session_start();

// debuging disable when not needed
$debug = FALSE;
$debug = TRUE;


if ($debug) {

    error_reporting(2047);
    ini_set("display_errors", 1);
}
else
    ini_set("display_errors", 0);

// end of debuging code
function debug($status) {
    global $debug;
//    DEBUG FUNCTION -------------------------------<<<<<
    if ($debug) {
        print_r($status);
        echo "<br/>\n";
    }
}

function loadLibs($libList) {
    global $reg;
        foreach ($libList as $lib) {
            require_once("core/lib/".$lib.".php");
            $reg->$lib = new $lib();
        }
    }
    
spl_autoload_register('my_autoloader');

##load registry
require_once 'core/Registry.php';
$reg = new Registry();
debug("registry  ...ok");

## load core config
require_once('core/config/conf.php');
debug("config  ...ok");

//$reg->loadLibs($main_core_libs);
loadLibs($core_libs);

## these are the base classes so that any class can extend them
require_once(COREDIR . 'Core.php');
require_once(COREDIR . 'Controller.php');
require_once(COREDIR . 'Model.php');
require_once(COREDIR . 'View.php');
debug("main Controller/Model/View  ...included");
## end of critical includes
## enter custom code here it is not recomended to edit below this block
#######################################################################
## load app config
require_once(APPDIR . 'config/appconf.php');
$reg->appconf = $appconf;
debug("app config  ...ok");
#######################################################################
## end of custom code block
## DataStore for url params
$_URL = array();

//create debug array in registry for bottom print
$reg->debug = array();

## autoloader
/**
 *
 * @param string $class_name
 */

function my_autoloader($class_name) {
    debug("include $class_name file  ...start");
    $className = explode('_', $class_name);
    $path = APPDIR;
    $ext = ".php";
    foreach ($className as $key => $val) {
        $path .= $val . "/";
        if ($val == "views")
            $ext = ".phtml";
    }
    $path = substr($path, 0, strlen($path) - 1);

    if (file_exists("../" . $path . $ext)) {
        require_once($path . $ext);
        debug("include $path file  ...  done");
    } else {
        debug("include $path file  ...  ERROR!! no class");
        throw new Exception("FATAL ERROR: No CLASS FIND!!!($class_name on $path$ext)");
    }
}

## end of autoloader
## parse url data
$reg->urlParse->getLoadDetails($controller, $view);
$action = $view;

debug("parse url - got $controller, $view  ...  done");
## set prefix or default controller/action
if (empty($controller)) {
    $controller = "controllers_index";
    $view = "indexAction";
} else {
    $controller = "controllers_" . $controller;
    if (!empty($view)) {
        $view .= "Action";
    } else {
        $view = "indexAction";
    }
}

debug("find controller class - got $controller, $view  ...  done");

## initialize access controll
$access = new access($reg->user);
//foreach ($roleList as $roleLevel => $role) {
//    $roles->$role['name'] = new rolesLib($role['name'], $roleLevel);
//}
//$reg->roles = $roles;



## fly to sky
try {
    $control = new $controller(); //this isnt abstract class, netbeans wrong
    $reg->controller = $control; //register for after access
    // pack controller in Secure Box for automatic role menagment
    //$userRole = $reg->user->getRole();
    require_once (COREDIR . 'SecureBox.php');
    $control = new SecureBox($control, $access);

    // pass controller call to SecureBox check
    $control->$view();
} catch (Exception $e) {

    $reg->error->f404Static($e);
}

debug($reg);
debug($_SESSION);
debug($_URL);
?>
