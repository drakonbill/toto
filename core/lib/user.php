<?php

/* Class : user
 * Function : basic functions for user
 * Version : 0.1
 * Date : 16/07/13
 * @Author : Miki
 */

class user {

// Informations of the user
    private $userData;
    private $reg;

// All informations of the member are stocked in the variable $userData
    function __construct($id) {
        global $reg;
        $this->reg = $reg;

        $this->insertTable($reg->userTable, $reg->userIdFild, $id);
    }

    public function insertTable($table, $idTable, $id) {
        if (!isset($id))
            $id = "-1";
        $q = "SELECT * FROM " . $table . " where " . $idTable . " = $id";
        $query = mysql_query($q, $this->reg->dbcon) or die(mysql_error() . $q);
        while ($row = mysql_fetch_assoc($query)) {
            foreach ($row as $key => $value) {
                $this->userData[$key] = $value;
            }
        }
    }

// Get the information you need in the $name variable
    public function __get($name) {
        if (isset($this->userData[$name]))
            return $this->userData[$name];
        else
            return 0;
    }

// Change an information of the member
    function __set($name, $value) {
        $this->userData[$name] = $value;
    }

// Check if the information is stocked or not
    function __isset($name) {
        return isset($this->userData[$name]);
    }

// Delete a selected informations 
    function __delete($name) {
        if (isset($this->userData[$name])) {
            unset($this->userData[$name]);
        }
    }

// Update an information
    function __update($name, $value) {
        if ($this->userData[$name] != $value) {
            $this->userData[$name] = $value;
        }
    }

// Check is an id is stocked on a session or not : if the member is logged or not
    function loggedin() {
        return isset($_SESSION['id_member']);
    }

    function getRole() {
        global $roleList;
        return isset($this->level_member) ? $roleList[$this->level_member]['name'] : $roleList['-1'];
    }

}

?>
