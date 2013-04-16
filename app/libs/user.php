<?php

/* Class : libs_user
 * Function : have all informations on each page of the user actually logged
 * Version : 0.1
 * Date : 16/04/13
 * @Author : Miki and Quentin
 */

// For the fonction of crypting (password)
define("PREFIXE", "prefixeprotecmeeto");
define("SUFFIXE", "suffixeprotecparty");

class libs_user {

    // Informations of the user 
    private $userData;

    // All informations of the member are stocked in the global variable $userData
    function __construct($id) {
        global $reg;
        // If there is no ID in parametre 
        if (!isset($id))
            $id = "-1";

        // Select basic member informations + member_details
        $query = mysql_query("SELECT * FROM member M, member_details D WHERE M.id_member = D.id_member AND M.id_member = '$id'", $reg->dbcon) or die(mysql_error());
        while ($row = mysql_fetch_assoc($query)) {
            foreach ($row as $key => $value) {
                $this->userData[$key] = $value;
            }
        }
    }

    // Get the information you need in the $name variable
    function __get($name) {
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

    // Check is an id is stocked on a session or not : if the member is logged or not
    function loggedin() {
        return isset($_SESSION['id_member']);
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
    
    // To crypt the password of a member
    function hacher($passe) {
        $passe = md5(sha1(PREFIXE) . $passe . sha1(SUFFIXE));
        return $passe;
    }

}

?>
