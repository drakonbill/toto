<?php


/**
 * Description of event
 *
 * @author Deixonne
 */
class models_ajax_event extends Model {
    
     function verif_member() {
         $pseudomembre = strtolower($_POST['pseudomembre']);

        if(!empty($pseudomembre)) {
                $requete_verif_membre = mysql_query("SELECT id_member FROM member WHERE LOWER(pseudo_member)='".$pseudomembre."'") or die(mysql_error());
                $data_verif_membre = mysql_fetch_array($requete_verif_membre);

                if(!empty($data_verif_membre))
                        echo $data_verif_membre[0];
                else
                        echo "notok";

        }
        else {
                echo "notok";
        }
     }
}

?>
