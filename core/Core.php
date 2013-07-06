<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core
 *
 * @author Miki
 */
class Core {

    //put your code here
    protected $reg;
    protected $db;
    protected $option;
    protected $validate;
    protected $user;
    protected $conf;
    protected $role;

    public function __construct($option) {

        global $reg;
        $this->reg = $reg;
        $this->option = $option;
        $this->db = $reg->dbcon;
        $this->validate = $reg->validate;
        $this->user = $reg->user;
        $this->conf = $reg->conf;
        $this->role = $reg->roles;
    }

    public function addData($add) {
        $data = $reg->data;
        $data[] = $add;
        $reg->data = $data;
    }

}

?>
