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

    function ModifyPseudo() {
        $pseudo = $_POST['pseudo'];
        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member SET pseudo_member = '$pseudo' WHERE id_member = '$idmember'") or die(mysql_error());

        return $pseudo;
    }

    function ModifySituation() {

        $situation = $_POST['situation'];

        if ($situation == NCE) {
            $situation = NULL;
        } else if ($situation == SINGLE) {
            $situation = 1;
        } else if ($situation == COUPLE) {
            $situation = 2;
        } else if (($situation == BRIDE) OR ($situation == MARIED)) {
            $situation = 3;
        }
        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET statut_member = '$situation' WHERE id_member = '$idmember'") or die(mysql_error());

        return $situation;
    }

    function ModifyPreferance() {

        $preferance = $_POST['preferance'];

        if ($preferance == NC) {
            $preferance = NULL;
        } else if ($preferance == "Homme") {
            $preferance = P_M;
        } else if ($preferance == "Femme") {
            $situation = P_W;
        } else if ($preferance == "Homme et Femme") {
            $situation = P_B;
        }
        
        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member SET preference_member = '$preferance' WHERE id_member = '$idmember'") or die(mysql_error());

        return $situation;
    }

}

?>
