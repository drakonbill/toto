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
    public function getLoadDetails(&$controllerName, &$actionName){
    global $_URL;
            $filePath = explode('?',$_SERVER['REQUEST_URI']);
			$filePath = $filePath[0];
            $filePath = explode("/", $filePath);
            array_shift($filePath);

            $controllerName = array_shift($filePath);
            $actionName = array_shift($filePath);

            for($i = 0; $i < count($filePath); $i++){
                    $key = $filePath[$i];
                    $i++;
                    $val = @$filePath[$i];
                    $_URL[$key] = urldecode($val);
            }
    }
    ## end of the url parser
}

?>
