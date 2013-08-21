<?php

/**
 * Widget for viewing all passion of the member
 *
 * @author Quentin.L
 */
class models_widgets_profilPassion extends Model {

    public function passionCategory($idmember) {

        $requete_passion = mysql_query("SELECT * FROM passion_category");

        while ($result = mysql_fetch_assoc($requete_passion)) {
            $data[$result['id_category']] = $result;
        }

        $requete_memberpassion = mysql_query("SELECT * FROM passion_category C, member_passion M, passion P WHERE M.id_member = ".$idmember." AND P.id_passion = M.id_passion AND P.id_category = C.id_category");
        
        $i = 0;
        
        while ($result2 = mysql_fetch_assoc($requete_memberpassion)) {
            $data["datamember"][$i] = $result2;
            $i++;
        }

        return $data;
    }

}

?>
