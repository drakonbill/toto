<?php

/**
 * Widget for viewing all passion of the member
 *
 * @author Quentin.L
 */
class models_widgets_profilNavigation extends Model {

    public function profilNavigation($datasent) {

        $id = $datasent["id_member"];
        $own = $datasent["own"];

        $requete_member = mysql_query("SELECT * FROM member WHERE id_member = " . $id . "");

        while ($donnees = mysql_fetch_assoc($requete_member)) {
            $data["id_member"] = $donnees["id_member"];
            $data["own"] = $own;
            $data["pseudo_member"] = $donnees["pseudo_member"];
            $data["photo_member"] = $donnees["photo_member"];
        }
        return $data;
    }

}

?>
