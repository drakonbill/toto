<?php

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

        if ((stripos($pseudo, 'Mister-') !== FALSE) OR (stripos($pseudo, 'Miss-') !== FALSE)) {

            $requete_verif_level = mysql_query("SELECT * FROM member WHERE id_member ='" . $idmember . "'") or die(mysql_error());
            $data_verif_level = mysql_fetch_array($requete_verif_level);

            if ($data_verif_level['level_member'] == 9) {
                
                $requete_verif_pseudo = mysql_query("SELECT * FROM member WHERE pseudo_member ='" . $pseudo . "'") or die(mysql_error());
                $data_verif_pseudo = mysql_fetch_array($requete_verif_pseudo);

                if (empty($data_verif_pseudo)) {
                    mysql_query("UPDATE member SET pseudo_member = '$pseudo' WHERE id_member = '$idmember'") or die(mysql_error());
                    return $pseudo;
                } else {
                    $error = "alreadytake";
                    return $error;
                }
                
            } else {
                $error = "adminformat";
                return $error;
            }
        } else {
            $requete_verif_pseudo = mysql_query("SELECT * FROM member WHERE pseudo_member ='" . $pseudo . "'") or die(mysql_error());
            $data_verif_pseudo = mysql_fetch_array($requete_verif_pseudo);

            if (empty($data_verif_pseudo)) {
                mysql_query("UPDATE member SET pseudo_member = '$pseudo' WHERE id_member = '$idmember'") or die(mysql_error());
                return $pseudo;
            } else {
                $error = "alreadytake";
                return $error;
            }
        }
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

        return $preferance;
    }

    function ModifySexe() {

        $sexe = $_POST['sexe'];

        if ($sexe == "Homme") {
            $sexe = HOMME;
        } else if ($sexe == "Femme") {
            $sexe = FEMME;
        }

        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member SET sex_member = '$sexe' WHERE id_member = '$idmember'") or die(mysql_error());

        return $sexe;
    }

}
?>
