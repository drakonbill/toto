<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of passion
 *
 * @author Deixonne
 */
class models_ajax_passion extends Model {
    
    function choicePassion() {
        
        global $reg;
        
        $q = trim($_GET['q']);
        $result = "";
        
        if(!empty($q)) {
            $resultat = mysql_query("SELECT id_passion,name_passion,icon_passion AS icone,category_passion.icone AS icone_categorie,nomcategorie from passion inner join passion_category on passion.category_passion=passion_category.idcategorie WHERE name_passion LIKE '%".$q."%' LIMIT 11");
            while ($donnees = mysql_fetch_assoc($resultat)) {
                        $result .= ucfirst($donnees['name_passion'])."|".ucfirst($donnees['nomcategorie'])."|".(!empty($donnees['icone'])?$donnees['icone']:$donnees['icone_categorie'])."\n";
            }

            if(mysql_num_rows($resultat) == 0) {
                    $result = "Aucun résultat | |\n";
            }
        }
        
        return $result;
    }
}

?>
