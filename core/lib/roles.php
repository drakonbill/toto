<?php

class rolesLib {

    private $roleName, $roleLevel;
    private $class;

    function __construct($name, $level) {
        $this->roleName = $name;
        $this->roleLevel = $level;
        $this->class = new stdClass();
    }

    function add($class, $action) {
        $this->class->$class[$action] = $action;
    }

    function inRole($class, $action) {
        return $action == $this->class->$class[$action];
    }

}

?>