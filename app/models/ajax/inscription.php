<?php

/**
 * Description of models_ajax_inscription
 *
 * @author Miki
 */
class models_ajax_inscription extends Model {

    public function indexModel() {
        
    }

    public function inscription() {

        global $reg;
        $clean = $reg->clean;

        $pseudo = $clean->POST('register-pseudo');
        $email = $clean->POST('register-email');

        $error = "";
        $compteur = 0;

        if (!empty($pseudo)) {
            $requete = mysql_query("SELECT * from member WHERE pseudo_member = '$pseudo'") or die("Impossible de sélectionner l'pseudo : " . mysql_error());
            $result = mysql_fetch_assoc($requete);
            if (!empty($result['id_member'])) {
                $error = 1;
                $compteur++;
                //echo $error; //isnt ok to model do output, but for ajax i dont have fastest solution
            }
        }

        if (!empty($email)) {
            $requete2 = mysql_query("SELECT * from member WHERE email_member = '$email'") or die("Impossible de sélectionner l'email : " . mysql_error());
            $result2 = mysql_fetch_assoc($requete2);
            if (!empty($result2['id_member'])) {
                $error = 2;
                $compteur++;
                //echo $error;
            }
        }

        if ($compteur == 2) {
            $error = 3;
            //echo $error;
        }
        return $error;
    }

    function inscription2() {

        global $reg;
        $cp = $reg->clean->POST('cp');

        if (!empty($cp)) {
            $resultat = mysql_query("SELECT * from zip_code WHERE CP = '" . $cp . "'");

            while ($donnees = mysql_fetch_assoc($resultat)) {
                $ville[] = $donnees['Ville'];
            }
            
            if (!empty($ville)) {
                return json_encode($ville);
            } else if (empty($resultat)) {
                return "";
            }
        }
    }

}

?>
