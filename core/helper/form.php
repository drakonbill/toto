<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of form
 *
 * @author Miki
 */
class form {
   
    private $table;
    private $rows = array();
    
    public function __construct($table, $rows = array("*"=>"*")) {
        $this->rows = $rows;
        $this->table=$table;
    }
    
    public function generate(){
        global $reg;
        $q = "SELECT ".$this->getRows()." FROM ".$this->table;
        $result = mysql_query($q,$reg->dbcon);
        while (mysql_fetch_array($result)){
            
            // aaaa
        }
    }
}

?>
