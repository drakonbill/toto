<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Registry
 *
 * @author Miki
 */
class Registry {

    protected $vars = array();

    public function __set($index, $value) {
        $this->vars[$index] = $value;
    }

    public function __get($index) {
        if (isset($this->vars[$index]))
            return $this->vars[$index];
        else
            return "";
    }

    public function __isset($index) {

        return @isset($this->vars[$index]);
    }

    public function __unset($index) {

        unset($this->vars[$index]);
    }

   

}

?>
