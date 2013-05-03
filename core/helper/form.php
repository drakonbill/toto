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
    private $method = "POST", $action = "#";

    public function __construct($table) {

        $this->table = $table;
        $this->construct();
    }

    public function construct() {
        global $reg;
        $tip = "text";
        $q = "DESC " . $this->table;
        $result = mysql_query($q, $reg->dbcon);
        while ($row = mysql_fetch_array($result)) {
 
            if (strpos($row['Type'], "char") or strpos($row['Type'], "char"))
                $tip = "text";
            else if (strpos($row['Type'], "boolean") or strpos($row['Type'], "int(1)"))
                $tip = "radio";
            
            $this->rows[$row['Field']] = array("type" => $tip);
        }
    }

    public function generate() {
      

        $gform = "<form method = '$this->method' action = '$this->action' >";
        foreach ($this->rows as $name => $tip){
            
            $gform .= "$name: <input type='$tip' name = '" . $name . "' /><br/>";
        }
        $gform .= "<input type='submit' name='Submit'/></form>";
        return $gform;
    }

}

?>
