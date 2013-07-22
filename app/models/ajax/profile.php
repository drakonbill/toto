<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of member
 *
 * @author Deixonne
 */
class models_ajax_profile extends Model {

    function ModifyFirstName() {
        $name = $_POST['first-name'];
        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET first_name_member = '$name' WHERE id_member = '$idmember'") or die(mysql_error());
        
        return $name;
    }

}

?>
