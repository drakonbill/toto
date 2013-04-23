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
class models_ajax_member extends Model {
    
     function verifMember () {
         $pseudomembre = strtolower($_POST['pseudomembre']);

        if(!empty($pseudomembre)) {
                $requete_verif_membre = mysql_query("SELECT id_member FROM member WHERE LOWER(pseudo_member)='".$pseudomembre."'") or die(mysql_error());
                $data_verif_membre = mysql_fetch_array($requete_verif_membre);

                if(!empty($data_verif_membre))
                        return $data_verif_membre[0];
                else
                        return "notok";

        }
        else {
                return "notok";
        }
     }
     
     
}

?>
