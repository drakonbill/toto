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
class models_profil extends Model {
    
    private function ageDatenaissance($age_datenaissance)
    {
            $age_datenaissance = explode('-',$age_datenaissance);
            //Si on veut vérifier à la date actuelle ( par défaut )
            if(empty($timestamp))
                    $timestamp = time();

            //On evalue l'age, à un an par exces
            $age = date('Y',$timestamp) - $age_datenaissance[0];

            //On retire un an si l'anniversaire n'est pas encore passé
            if($age_datenaissance[2] > date('n', $timestamp) || ( $age_datenaissance[2]== date('n', $timestamp) && $age_datenaissance[1] > date('j', $timestamp)))
            $age--;

    return $age;
    }
    
    private function ajouterContact($id)
    {
            if($id != $_SESSION['id_member'])
            {
                    $requete = mysql_query("SELECT * FROM member_contacts WHERE id_member=".$_SESSION['id_member']." AND id_contact=".$id." OR id_member=".$id." AND id_contact=".$_SESSION['id_member']."");
                    
                    $etat = array();
                    while($data = mysql_fetch_array($requete))
                    {
                            $etat[$data['id_member']] = $data['etat'];
                    }

                    if(empty($etat))
                    {
                            $this->msg['ajoutercontact']="<a href='#ajouterContact' id='ajouterContact'>Ajouter ce contact</a>";
                    }
                    else
                    {
                            if($etat[$_SESSION['id_member']] == 0 && $etat[$id] == 1)
                                    $this->msg['ajoutercontact']="<a href='#ajouterContact' id='ajouterContact'>Accepter ce contact</a> | <a href='#refuserContact' id='refuserContact'>Ne pas accepter</a>";
                            else if($etat[$_SESSION['id_member']] == 1 && $etat[$id] == 0)
                                    $this->msg['ajoutercontact']="En attente de confirmation";
                            else if($etat[$_SESSION['id_member']] == 1 && $etat[$id] == 1)
                                    $this->msg['ajoutercontact']="<a href='#ajouterContact' id='retirerContact'>Retirer de mes contacts</a>";
                    }
            }
    }
        
    function seePassion($pseudo) {
        
        if(!empty($pseudo))
            $requete = mysql_query("SELECT * FROM member WHERE pseudo_member=".$pseudo."");
        else
            $requete = mysql_query("SELECT * FROM member WHERE id_member=".$_SESSION['id_member']."");
        $data = mysql_fetch_assoc($requete);
        
        if(!empty($data['id_member']))
        {
                
                $this->profile_id_member=$data['id_member'];
                $this->profile_pseudo=$data['pseudo_member'];
                $this->profile_datenaissance=$data['birth_member'];
                $this->profile_age=$this->ageDatenaissance($this->profile_datenaissance);
                $this->profile_maphoto=($data['photo_member'] != "" ? $data['photo_member']:($data['sex_member'] == 1 ? 'Images/no_avatar_homme.png' : 'Images/no_avatar_femme.png'));
                $this->profile_sexe=($data['sex_member'] == 1 ? 'Homme' : 'Femme');
                $this->profile_ville=$data['city_member'];
                $this->ajouterContact($this->profile_id_member);

                $this->profile_passion = array();
                $requete_passion = mysql_query("SELECT *,passion.id_category AS idcat, passion.icon_passion AS icone,passion_category.icone AS icone_cat FROM member_passion inner join passion on member_passion.id_passion=passion.id_passion inner join passion_category on passion.id_category=passion_category.id_category WHERE member_passion.id_member='".$_SESSION['id_member']."' ORDER BY name_category ASC") or die(mysql_error());
                while($data_passion = mysql_fetch_array($requete_passion)) {
                        $this->profile_passion[$data_passion['idcat']][] = $data_passion;
                        $this->profile_passioncat[$data_passion['idcat']] = $data_passion['name_category'];
                }
        }
        else
        {
                $this->error['profil']="Ce contact n'existe pas";
        }
        
        return $this;
    }
}

?>