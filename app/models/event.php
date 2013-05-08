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
                
                $requete_quivient = "SELECT * FROM event_participant inner join member on event_participant.id_member=member.id_member WHERE id_event='".$id_event."'";
                $this->resultat_quivient = mysql_query($requete_quivient) or die(mysql_error());
                $this->nb_quivient = mysql_num_rows($this->resultat_quivient);
                $this->quivient = array();
                while($data_quivient = mysql_fetch_array($this->resultat_quivient)) {
                        $this->quivient[] = $data_quivient;
                }
                        
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
                
                $requete_verif_participate = mysql_query("SELECT * FROM event_participant WHERE id_event='".$this->idevent."' AND id_member='".$_SESSION['id_member']."'") or die(mysql_error());
                $data_verif_participate = mysql_fetch_array($requete_verif_participate);
                $nb_verif_participate = mysql_num_rows($requete_verif_participate);

                if($nb_verif_participate == 1 && $data_verif_participate['status'] == '1') 
                        $this->link_participate = "Je ne viens plus";
                else if($nb_verif_participate == 1 && $data_verif_participate['status'] == '0') 
                        $this->link_participate = "J'annule ma demande";
                else 
                        $this->link_participate = "Je viendrai";

                $requete_verif_fan = mysql_query("SELECT * FROM event_fan WHERE id_event='".$this->idevent."' AND id_member='".$_SESSION['id_member']."'") or die(mysql_error());
                $nb_verif_fan = mysql_num_rows($requete_verif_fan);

                if($nb_verif_fan == 1) 
                        $this->link_fan = "Je ne suis plus fan";
                else 
                        $this->link_fan = "Je suis fan";

                $this->note="";
                for($i=0; $i <= 5; $i++)
                        $this->note.="<option value='".$i."'>".$i."</option>";
                        
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
    
    function modifyModel() {
        
        global $_URL;
        
        $this->ok=false;
		
        if(isset($_URL['id']) && is_numeric($_URL['id'])) {
                $id_event = $_URL['id'];
                $requete = "SELECT * FROM event inner join member on event.id_member=member.id_member WHERE id_event='".$id_event."' AND event.id_member='".$_SESSION['id_member']."'";
                $resultat = mysql_query($requete) or die(mysql_error());
                $data = mysql_fetch_array($resultat);	
                $nb_data = mysql_num_rows($resultat);

                if($nb_data == 1) {

                        $this->passion = array();
                        $requete_passion = "SELECT passion.icon_passion AS iconepassion, passion_category.icone AS iconecat, name_passion, name_category, passion.id_passion FROM event_passion inner join passion on event_passion.id_passion=passion.id_passion inner join passion_category on passion.id_category=passion_category.id_category WHERE id_event='".$id_event."'";
                        $data_passion = mysql_query($requete_passion) or die(mysql_error());
                        while($passion = mysql_fetch_array($data_passion))
                                $this->data_passion[] = $passion;
                        

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
                        $this->time_option_debut = $data['time_option_start_event'];
                        $this->time_option_fin = $data['time_option_end_event'];
                        $this->datedebut_hour = date("H", strtotime($data['start_date_event']));
                        $this->datedebut_minute = date("i", strtotime($data['start_date_event']));
                        $this->datedebut_day = date("d", strtotime($data['start_date_event']));
                        $this->datedebut_month = date("m", strtotime($data['start_date_event']));
                        $this->datedebut_year = date("Y", strtotime($data['start_date_event']));
                        $this->datefin_hour = date("H", strtotime($data['end_date_event']));
                        $this->datefin_minute = date("i", strtotime($data['end_date_event']));
                        $this->datefin_day = date("d", strtotime($data['end_date_event']));
                        $this->datefin_month = date("m", strtotime($data['end_date_event']));
                        $this->datefin_year = date("Y", strtotime($data['end_date_event']));

                        $this->codepostal = $data['zipcode_event'];
                        $this->ville = $data['city_event'];
                        $this->pays = $data['country_event'];
                        $this->lieu = $data['address_event'];
                        $this->confidentialite = $data['confidentiality_event'];
                        //$this->nb_participate = $data['nbparticipant'];
                        
                        $this->participant = array();
                        if($this->confidentialite == 2) {
                            $requete_participant = "SELECT * FROM event_confidentiality inner join member on event_confidentiality.id_member=member.id_member WHERE id_event='".$id_event."'";
                            $data_participant = mysql_query($requete_participant) or die(mysql_error());
                            while($participant = mysql_fetch_array($data_participant))
                                    $this->participant[] = $participant;
                        }

                        $this->longitude = $data['longitude_event'];
                        $this->latitude = $data['latitude_event'];
                        $this->ok=true;
                }
        }
        
        return $this;
    }
    
}

?>
