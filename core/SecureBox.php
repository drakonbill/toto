<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SecureBox
 *
 * @author Miki
 */
class SecureBox {

   
    protected $target = null;
    protected $role = null;

    public function __construct($target, $acl) {
        $this->target = $target;
        $this->role = $acl;
    }

    public function __call($method, $arguments) {
        global $reg;
         
        if (method_exists($this->target, $method) && $this->role->inRole(get_class($this->target), $method)) {

            return call_user_func_array(array($this->target, $method), $arguments);
        }
        else {
       
        return $reg->error->f404Static("");
        
        }
    }

}

?>
