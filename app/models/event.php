<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of event
 *
 * @author Deixonne
 */
class models_event extends Model {
    
    function addEventModel() {
        
        
        return true;
    }
    
    function displayModel() {
        
        global $_URL;
        $result = array();
        $result['return']="";
        
        if(isset($_URL['id'])) {
            
            $id_event = $_URL['id'];
            $requete = "SELECT * FROM event inner join member on event.id_member=member.id_member WHERE id_event='".$id_event."'";
            $resultat = mysql_query($requete) or die(mysql_error());
            $nb_data = mysql_num_rows($resultat);
            
            if($nb_data == 1) {
                $data = mysql_fetch_array($resultat);	

                $requete_passion = "SELECT passion.icon_passion AS iconepassion, passion_category.icone AS iconecat, name_passion, name_category FROM event_passion inner join passion on event_passion.id_passion=passion.id_passion inner join passion_category on passion.id_category=passion_category.id_category WHERE id_event='".$id_event."'";
                $this->data_passion = mysql_query($requete_passion) or die(mysql_error());

                // REMPLACER PAR UNION DATE 4 AVANT ET 4 APRES
                $this->ok=false;
               // $requete_myevent = "SELECT * FROM event WHERE id_member IN (SELECT idducontact FROM contacts WHERE iddumembre IN (SELECT idducontact FROM contacts WHERE idducontact=".$_SESSION['iddumembre']." AND etat=1) AND etat=1) AND UNIX_TIMESTAMP('".$data['datedebut']."') > UNIX_TIMESTAMP(datedebut) AND confidentialite=0 OR iddumembre=".$_SESSION['iddumembre']." AND UNIX_TIMESTAMP('".$data['datedebut']."') > UNIX_TIMESTAMP(datedebut) UNION ";
                //$requete_myevent .= "SELECT * FROM event WHERE id_member IN (SELECT idducontact FROM contacts WHERE iddumembre IN (SELECT idducontact FROM contacts WHERE idducontact=".$_SESSION['iddumembre']." AND etat=1) AND etat=1) AND UNIX_TIMESTAMP('".$data['datedebut']."') <= UNIX_TIMESTAMP(datedebut) AND confidentialite=0 OR iddumembre=".$_SESSION['iddumembre']." AND UNIX_TIMESTAMP('".$data['datedebut']."') <= UNIX_TIMESTAMP(datedebut)";
              //  $this->resultat_myevent = mysql_query($requete_myevent) or die(mysql_error());

                $this->idevent = $id_event;
                $this->nomevenement = $data['name_event'];
                $this->description = $data['description_event'];
                $this->image = $data['image_event'];
                $this->identreprise = $data['id_company'];
                $this->iddumembre = $data['id_member'];
                $this->auteur = $data['pseudo_member'];
                $this->avatar = $data['photo_member'];

                $this->lon = $data['longitude_event'];
                $this->lat = $data['latitude_event'];

                $Jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi","Samedi");
                $Mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
                $this->Mois = $Mois;
                $this->datedebut = strtotime($data['start_date_event']);
                $this->datefin = strtotime($data['end_date_event']);

                $this->termine=false;
                if($this->datedebut > time()) {

                }
                else {
                        $this->termine=true;
                }
                
                $requete_mycomment = "SELECT * FROM event_comments inner join member on event_comments.id_member=member.id_member WHERE id_event='".$id_event."' ORDER BY date_comment DESC LIMIT 15";
		$this->resultat_mycomment = mysql_query($requete_mycomment) or die(mysql_error());

                if(date("Y m d", $this->datedebut) == date("Y m d", $this->datefin)) {
                        $this->date_result = " <strong>le ".$Jour[date("w", $this->datedebut)]." ".date("d", $this->datedebut)." ".$Mois[date("n", $this->datedebut)-1]." ".date("Y", $this->datedebut)."</strong>";
                        if($data['time_option_start_event'] && $data['time_option_end_event'])
                                $this->date_result .= " <span class='grey'>de ".date("H:i", $this->datedebut)." jusqu'à ".date("H:i", $this->datefin)."</span>";
                        else if($data['time_option_start_event'] && !$data['time_option_end_event'])
                                $this->date_result .= " <span class='grey'>à partir de ".date("H:i", $this->datedebut)."</span>";
                        else if(!$data['time_option_start_event'] && $data['time_option_end_event'])
                                $this->date_result .= " <span class='grey'>jusqu'à ".date("H:i", $this->datefin)."</span>";
                }
                else {
                        if($data['time_option_start_event'] && $data['time_option_end_event']) {
                                $this->date_result = " <strong>du ".$Jour[date("w", $this->datedebut)]." ".date("d", $this->datedebut)." ".$Mois[date("n", $this->datedebut)-1]." ".date("Y", $this->datedebut)." </strong><span class='grey'>à partir de ".date("H:i", $this->datedebut)."</span>";
                                $this->date_result .= " <strong>au ".$Jour[date("w", $this->datefin)]." ".date("d", $this->datefin)." ".$Mois[date("n", $this->datefin)-1]." ".date("Y", $this->datefin)." </strong><span class='grey'>jusqu'à ".date("H:i", $this->datefin)."</span>";
                        }
                        else if($data['time_option_start_event'] && !$data['time_option_end_event']) {
                                $this->date_result = " <strong>du ".$Jour[date("w", $this->datedebut)]." ".date("d", $this->datedebut)." ".$Mois[date("n", $this->datedebut)-1]." ".date("Y", $this->datedebut)." </strong><span class='grey'>à partir de ".date("H:i", $this->datedebut)."</span>";
                                $this->date_result .= " <strong>au ".$Jour[date("w", $this->datefin)]." ".date("d", $this->datefin)." ".$Mois[date("n", $this->datefin)-1]." ".date("Y", $this->datefin)."</strong>";
                        }
                        else if(!$data['time_option_start_event'] && $data['time_option_end_event']) {
                                $this->date_result = " <strong>du ".$Jour[date("w", $this->datedebut)]." ".date("d", $this->datedebut)." ".$Mois[date("n", $this->datedebut)-1]." ".date("Y", $this->datedebut)."</strong>";
                                $this->date_result .= " <strong>au ".$Jour[date("w", $this->datefin)]." ".date("d", $this->datefin)." ".$Mois[date("n", $this->datefin)-1]." ".date("Y", $this->datefin)." </strong><span class='grey'>jusqu'à ".date("H:i", $this->datefin)."</span>";
                        }
                        else if(!$data['time_option_start_event'] && !$data['time_option_end_event']) {
                                $this->date_result = " <strong>du ".$Jour[date("w", $this->datedebut)]." ".date("d", $this->datedebut)." ".$Mois[date("n", $this->datedebut)-1]." ".date("Y", $this->datedebut);
                                $this->date_result .= "  au ".$Jour[date("w", $this->datefin)]." ".date("d", $this->datefin)." ".$Mois[date("n", $this->datefin)-1]." ".date("Y", $this->datefin)."</strong>";
                        }
                }


                $this->codepostal = $data['zipcode_event'];
                $this->ville = $data['city_event'];
                $this->pays = $data['country_event'];
                $this->lieu = $data['address_event'];
                $this->confidentialite = $data['confidentiality_event'];
                $this->confidentialite_texte="";
                switch($this->confidentialite) {
                        case '0':
                                $this->confidentialite_texte="Evènement privé";
                        break;
                        case '1':
                                $this->confidentialite_texte="Evènement public";
                        break;
                        case '2':
                                $this->confidentialite_texte="Evènement privé";
                        break;
                }
                $this->longitude = $data['longitude_event'];
                $this->latitude = $data['latitude_event'];
                $this->ok=true;
            }
            else
                $this->ok=false;
        }
        else {
            $this->ok=false;
        }
        
        return $this;
    }
}

?>
