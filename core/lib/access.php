<?php

class access {

    private $class;

    function __construct($user) {
        
        //$this->name = $name;
        //$this->roleLevel = $level;
        $this->class = new stdClass();
    }

    function add($class, $action) {
        $this->class->$class[$action] = $action;
    }

    function inRole($class, $action) {
        return 1;//$action == $this->class->$class[$action];
    }

}

?>