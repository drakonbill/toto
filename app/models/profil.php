<?php

/**
 * Model : Profil : all functions relatives to the profil of the member
 * @author Nicolas D, Quentin L
 * @todo : Clean functions
 */
class models_profil extends Model {
    /*
     * No using for the moment, because default function in controller is using another function in the model.
     */

    function indexModel() {
        
    }

    function seeProfil($pseudo) {

        if ($pseudo == "") {
            $pseudoData = $this->reg->user;
        } else {
            $id = libs_user::getID($pseudo);
            if ($id)
                $pseudoData = new libs_user($id);
            else
                $pseudoData->error = "Ce contact n'existe pas.";
        }

        return $pseudoData;
    }

    function seeProfilID($id) {

        if ($id == "") {
            $pseudoData = $this->reg->user;
        } else {

            $pseudoData = new libs_user($id);
            if (!$pseudoData->id_member)
                $pseudoData->error = "Ce contact n'existe pas.";
        }

        $pseudoData->addcontact = $this->ajouterContact($pseudoData->id_member);

        return $pseudoData;
    }

    private function ageDatenaissance($age_datenaissance) {
        $age_datenaissance = explode('-', $age_datenaissance);
        //Si on veut vérifier à la date actuelle ( par défaut )
        if (empty($timestamp))
            $timestamp = time();

        //On evalue l'age, à un an par exces
        $age = date('Y', $timestamp) - $age_datenaissance[0];

        //On retire un an si l'anniversaire n'est pas encore passé
        if ($age_datenaissance[2] > date('n', $timestamp) || ( $age_datenaissance[2] == date('n', $timestamp) && $age_datenaissance[1] > date('j', $timestamp)))
            $age--;

        return $age;
    }

    private function ajouterContact($id) {
        if ($id != $_SESSION['id_member']) {
            $requete = mysql_query("SELECT * FROM member_contacts WHERE id_member=" . $_SESSION['id_member'] . " AND id_contact=" . $id . " OR id_member=" . $id . " AND id_contact=" . $_SESSION['id_member'] . "");

            $etat = array();
            $msg = '';
            while ($data = mysql_fetch_array($requete)) {
                $etat[$data['id_member']] = $data['condition_contact'];
            }

            if (empty($etat)) {
                $msg = "<a href='javascript:void()' id='ajouterContact' class='details-btn'>Ajouter comme ami</a>";
            } else {
                if ($etat[$_SESSION['id_member']] == 0 && $etat[$id] == 1)
                    $msg = "<a href='javascript:void()' id='ajouterContact' class='details-btn'>Accepter ce contact</a> | <a class='details-btn' href='#refuserContact' id='refuserContact'>Ne pas accepter</a>";
                else if ($etat[$_SESSION['id_member']] == 1 && $etat[$id] == 0)
                    $msg = "<a href='javascript:void()' id='cancelContact' class='details-btn'>Annuler ma demande</a>";
                else if ($etat[$_SESSION['id_member']] == 1 && $etat[$id] == 1)
                    $msg = "<a href='javascript:void()' id='retirerContact' class='details-btn'>Retirer de mes contacts</a>";
            }

            return $msg;
        }
    }

    function seePassion($pseudo) {

        if (!empty($pseudo))
            $requete = mysql_query("SELECT * FROM member WHERE pseudo_member=" . $pseudo . "");
        else
            $requete = mysql_query("SELECT * FROM member WHERE id_member=" . $_SESSION['id_member'] . "");
        $data = mysql_fetch_assoc($requete);

        if (!empty($data['id_member'])) {

            $this->profile_id_member = $data['id_member'];
            $this->profile_pseudo = $data['pseudo_member'];
            $this->profile_datenaissance = $data['birth_member'];
            $this->profile_age = $this->ageDatenaissance($this->profile_datenaissance);
            $this->profile_maphoto = ($data['photo_member'] != "" ? $data['photo_member'] : ($data['sex_member'] == 1 ? 'Images/no_avatar_homme.png' : 'Images/no_avatar_femme.png'));
            $this->profile_sexe = ($data['sex_member'] == 1 ? 'Homme' : 'Femme');
            $this->profile_ville = $data['city_member'];
            $this->ajouterContact($this->profile_id_member);

            $this->profile_passion = array();
            $requete_passion = mysql_query("SELECT *,passion.id_category AS idcat, passion.icon_passion AS icone,passion_category.icone AS icone_cat FROM member_passion inner join passion on member_passion.id_passion=passion.id_passion inner join passion_category on passion.id_category=passion_category.id_category WHERE member_passion.id_member='" . $_SESSION['id_member'] . "' ORDER BY name_category ASC") or die(mysql_error());
            while ($data_passion = mysql_fetch_array($requete_passion)) {
                $this->profile_passion[$data_passion['idcat']][] = $data_passion;
                $this->profile_passioncat[$data_passion['idcat']] = $data_passion['name_category'];
            }
        } else {
            $this->error['profil'] = "Ce contact n'existe pas";
        }

        return $this;
    }

    function seeContacts($id) {
        
        if (!empty($id))
            $requete = mysql_query("SELECT * FROM member WHERE id_member=" . $id . "");
        else
            $requete = mysql_query("SELECT * FROM member WHERE id_member=" . $_SESSION['id_member'] . "");
        $data = mysql_fetch_assoc($requete);

        if (!empty($data['id_member'])) {

            $this->profile_id_member = $data['id_member'];
            $this->profile_pseudo = $data['pseudo_member'];
            $this->profile_datenaissance = $data['birth_member'];
            $this->profile_age = $this->ageDatenaissance($this->profile_datenaissance);
            $this->profile_maphoto = ($data['photo_member'] != "" ? $data['photo_member'] : ($data['sex_member'] == 1 ? 'Images/no_avatar_homme.png' : 'Images/no_avatar_femme.png'));
            $this->profile_sexe = ($data['sex_member'] == 1 ? 'Homme' : 'Femme');
            $this->profile_ville = $data['city_member'];
            $this->ajouterContact($this->profile_id_member);
            
        } else {
            $this->error['profil'] = "Ce contact n'existe pas";
        }

        return $this;
    }

}

?>