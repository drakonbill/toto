<?php

class roles {

    private $roleName, $pages = array();

    function __construct($name) {
        $this->roleName = $name;
    }

    function addPage($page) {
        $this->pages[$page] = $page;
    }
    
    function isRole($page){
        return in_array($page, $this->pages);
    }

}

?>