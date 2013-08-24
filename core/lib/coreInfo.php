<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of coreInfo
 *
 * @author Miki
 */
class coreInfo {

    function getControllersList() { // Get controller class names
        
        $classes = get_declared_classes();
        foreach (glob("../".APPDIR."controllers/*.php") as $value) {
            include_once $value;
        }
        
        return array_diff(get_declared_classes(), $classes);
    }

}

?>
