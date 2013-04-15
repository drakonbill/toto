<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author Miki
 */
class libs_user {

    private $userData;

    function __construct($id) { //fill with data
        global $reg;
        if (!isset($id))
            $id = "-1";
        $data = mysql_query("SELECT * from member WHERE id_member = '$id'", $reg->dbcon);
        while ($row = mysql_fetch_assoc($data)) {
            foreach ($row as $key => $value) {
                $this->userData[$key] = $value;
            }
        }
    }

    function __get($name) {
        if (isset($this->userData[$name]))
            return $this->userData[$name];
        else
            return 0;
    }

    function __set($name, $value) {
        $this->userData[$name] = $value;
    }

    function __isset($name) {
        return isset($this->userData[$name]);
    }
    function loggedin(){
        return isset($_SESSION['idmember']);
        
    }
}

?>
