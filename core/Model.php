<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author Miki
 */
abstract class Model {

    //put your code here
    protected $reg;
    protected $db;

    public function __construct() {
        global $reg;
        $this->reg = $reg;
        $this->db = $reg->dbcon;

        //$this->indexModel();
    }

    abstract function indexModel();
}

?>
