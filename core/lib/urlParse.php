<?php

/**
 * parses the url to get the controlla data and save the $_URL data
 *
 * @author Miki
 */
class urlParse {
    ## 
    /**
     *
     * @global system $_URL
     * @param string &$controlerName
     * @param string &$viewName
     */

    public function getLoadDetails(&$controllerName, &$actionName) {
        global $_URL, $reg;
        $filePath = explode('?', $_SERVER['REQUEST_URI']);
        $filePath = $filePath[0];
        $filePath = explode("/", $filePath);
        array_shift($filePath);

        $controllerName = array_shift($filePath);
        $actionName = array_shift($filePath);

        //API URL parse addon
        if (in_array($controllerName, $reg->api)) {
            $controllerName = $controllerName . "_" . ($actionName == "" ? "index" : $actionName);
            $actionName = "";//$filePath[0];
            $actionName != "" ? $actionName .= ucfirst(strtolower($_SERVER['REQUEST_METHOD'])) : $actionName = "index" . ucfirst(strtolower($_SERVER['REQUEST_METHOD'])); //Add GET/PUT/PUSH/DELETE on end of action name
        }

        for ($i = 0; $i < count($filePath); $i++) {
            $key = $filePath[$i];
            $i++;
            $val = @$filePath[$i];
            $_URL[$key] = urldecode($val);
        }
    }

    ## end of the url parser
}

?>
