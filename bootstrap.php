<?php

// debuging disable when not needed
error_reporting(2047);
ini_set("display_errors", 1);

// end of debuging code
function debug($status) {
//    DEBUG FUNCTION -------------------------------<<<<<
    print_r($status) ; echo "<br/>\n";
}

## start the sessions
session_start();

spl_autoload_register('my_autoloader');
## load core config
require_once('core/config/conf.php');

## load some libs (move to config??)
require_once(COREDIR . 'lib/error.php');
require_once(COREDIR . 'lib/urlParse.php');
require_once(COREDIR . 'lib/cleanData.php');
require_once(COREDIR . 'lib/libMySQL.php');

debug("libs  ...ok");
##load registry
require_once 'core/Registry.php';
$reg = new Registry();

debug("registry  ...ok");
##add to Registry (move to config?!?)
$reg->clean = new cleanData(); //this lib dont have extra setings, and can be loaded
$reg->error = new error();
$reg->urlParse = new urlParse();

debug("registry  ...filling with libs");
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

## fly to sky
try {
    $control = new $controller(); //this will do __autoload call
    $reg->controller = $control; //register for after access
    //$control->action = $action;
    //$control->controller = $controller;
    if (method_exists($control, $view)) {
        $control->$view();
    } else {
        $reg->error->f404Static("");
    }
} catch (Exception $e) {
    $reg->error->f404Static($e);
}

debug($reg);
debug($_SESSION);
?>