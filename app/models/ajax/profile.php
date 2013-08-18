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

    function ModifyBirth() {

        $birth = $_POST['birth'];

        $idmember = $_SESSION['id_member'];

        list($jour, $mois, $annee) = split('[/.]', $birth);
        $newbirth = "$annee-$mois-$jour";
        mysql_query("UPDATE member SET birth_member = '$newbirth' WHERE id_member = '$idmember'") or die(mysql_error());

        return $birth;
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

    function ModifySituationpro() {

        $situationpro = $_POST['situationpro'];

        if ($situationpro == NCE) {
            $situationpro = NULL;
        } else if ($situationpro == "Collège") {
            $situation = COLLEGE;
        } else if ($situationpro == "Lycée") {
            $situationpro = LYCEE;
        } else if ($situationpro == "Bac 1") {
            $situationpro = BAC1;
        } else if ($situationpro == "Bac 2") {
            $situationpro = BAC2;
        } else if ($situationpro == "Bac 3") {
            $situationpro = BAC3;
        } else if ($situationpro == "Bac 4") {
            $situationpro = BAC4;
        } else if ($situationpro == "Bac 5") {
            $situationpro = BAC5;
        } else if ($situationpro == "Bac 6") {
            $situationpro = BAC6;
        } else if ($situationpro == "Employé") {
            $situationpro = EMPLOYEE;
        } else if ($situationpro == "Freelance") {
            $situationpro = FREELANCE;
        } else if ($situationpro == "Sans emploi") {
            $situationpro = JOBLOOKING;
        }
        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET study_member = '$situationpro' WHERE id_member = '$idmember'") or die(mysql_error());

        return $situation;
    }

    function ModifyCodepostal() {

        $codepostal = $_POST['codepostal'];
        $idmember = $_SESSION['id_member'];

        $requete_verif_codepostal = mysql_query("SELECT * FROM zip_code WHERE CP ='" . $codepostal . "'") or die(mysql_error());
        $data_verif_codepostal = mysql_fetch_array($requete_verif_codepostal);

        if (!empty($data_verif_codepostal['CP'])) {

            mysql_query("UPDATE member SET zipcode_member = '$codepostal' WHERE id_member = '$idmember'") or die(mysql_error());
            return $codepostal;
        } else {
            $error = "codeinconnu";
            return $error;
        }
    }

    function ModifyVille() {

        $ville = $_POST['ville'];
        $codepostal = $_POST['codepostal'];
        $idmember = $_SESSION['id_member'];

        // Traitement pour que le nom de la ville soit en concordance avec la BDD
        // Remplacement des accents avec les E 
        $e_accent = array("ê", "é", "è", "È", "É", "Ê", "Ë");
        $villeclean1 = str_replace($e_accent, "e", $ville);

        // Remplacement des accents avec les A 
        $a_accent = array("Â", "â");
        $villeclean2 = str_replace($a_accent, "a", $villeclean1);

        // Remplacement des accents avec les I 
        $i_accent = array("Î", "î");
        $villeclean3 = str_replace($i_accent, "i", $villeclean2);

        // Suppression des _ et des -
        $tiret = array("-", "_");
        $villeclean4 = str_replace($tiret, " ", $villeclean3);

        // Remplacement de Saint par ST pour rester en concordance avec la BDD
        $saint = array("saint", "Saint", "SAINT");
        $villeclean5 = str_replace($saint, "st", $villeclean4);

        $villeclean = strtoupper($villeclean5);

        $requete_verif_ville = mysql_query("SELECT * FROM zip_code WHERE Ville ='" . $villeclean . "'") or die(mysql_error());
        $data_verif_ville = mysql_fetch_array($requete_verif_ville);

        if (!empty($data_verif_ville['Ville'])) {

            $requete_verif_codepostal = mysql_query("SELECT * FROM zip_code WHERE Ville ='" . $villeclean . "' AND CP ='" . $codepostal . "'") or die(mysql_error());
            $data_verif_codepostal = mysql_fetch_array($requete_verif_codepostal);

            if (!empty($data_verif_codepostal)) {

                $lien = "http://maps.google.com/maps/api/geocode/json?address=$codepostal+$ville&sensor=false";
                $geocode = file_get_contents("$lien");

                $output = json_decode($geocode);

                $lat = $output->results[0]->geometry->location->lat;
                $long = $output->results[0]->geometry->location->lng;

                mysql_query("UPDATE member SET city_member = '$villeclean', long_member = '$long', lati_member = '$lat' WHERE id_member = '$idmember'") or die(mysql_error());
                return $ville;
            } else {
                $error = "codepostalerror";
                return $error;
            }
        } else {
            $error = "villeinconnue";
            return $error;
        }
    }

    function ModifyPortable() {

        $portable = $_POST['portable'];

        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET cellular_member = '$portable' WHERE id_member = '$idmember'") or die(mysql_error());

        return $portable;
    }

    function ModifyRecherche() {

        $recherche = $_POST['recherche'];

        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET description_research_member = '$recherche' WHERE id_member = '$idmember'") or die(mysql_error());

        return $recherche;
    }

    function ModifyDescription() {

        $description = $_POST['description'];

        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET description_membre = '$description' WHERE id_member = '$idmember'") or die(mysql_error());

        return $description;
    }

    function ModifyFacebook() {

        $facebook = $_POST['facebook'];

        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET facebook_member = '$facebook' WHERE id_member = '$idmember'") or die(mysql_error());

        return $facebook;
    }

    function ModifyTwitter() {

        $twitter = $_POST['twitter'];

        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET twitter_member = '$twitter' WHERE id_member = '$idmember'") or die(mysql_error());

        return $twitter;
    }

    function ModifySkype() {

        $skype = $_POST['skype'];

        $idmember = $_SESSION['id_member'];

        mysql_query("UPDATE member_details SET skype_member = '$skype' WHERE id_member = '$idmember'") or die(mysql_error());

        return $twitter;
    }

}

?>
