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
         
        $q = $reg->clean->post($_GET['q']);
        $result = "";
        
        if(!empty($q)) {
            $resultat = mysql_query("SELECT id_passion,name_passion,icon_passion AS icone,passion_category.icone AS icone_categorie,nomcategorie from passion inner join passion_category on passion.category_passion=passion_category.idcategorie WHERE name_passion LIKE '%".$q."%' LIMIT 11") or die(mysql_error());
            while ($donnees = mysql_fetch_assoc($resultat)) {
                        $result .= ucfirst($donnees['name_passion'])."|".ucfirst($donnees['nomcategorie'])."|".(!empty($donnees['icone'])?$donnees['icone']:$donnees['icone_categorie'])."\n";
            }

            if(mysql_num_rows($resultat) == 0) {
                    $result = "Aucun résultat | |\n";
            }
        }
        
        return $result;
    }
    
    function registerPassionEvent() {
        
        global $reg;
        
        $result = "";
        
        if($reg->validate->Numeric($_SESSION['iddumembre']) != "")
        {

                $passion = $reg->clean->post($_POST['passion']);
                $categorie = intval($_POST['categorie']);

                if(isset($_POST['x1']) && isset($_POST['x2']) && isset($_POST['y1']) && isset($_POST['y2']) && isset($_POST['w']) && isset($_POST['h']) && isset($_POST['image']) && isset($_POST['imageheight']) && isset($_POST['imagewidth'])) {
                        $x1 = intval($_POST['x1']);
                        $y1 = intval($_POST['y1']);
                        $x2 = intval($_POST['x2']);
                        $y2 = intval($_POST['y2']);
                        $w = intval($_POST['w']);
                        $h = intval($_POST['h']);
                        $image = $reg->clean->post($_POST['image']);
                        $imageheight = intval($_POST['imageheight']);
                        $imagewidth = intval($_POST['imagewidth']);
                }

                if(!empty($passion))
                {
                        $requete_verif_new = mysql_query("SELECT passion.id_passion,name_passion,nomcategorie,passion_category.icone,passion.icone AS icone_passion FROM passion inner join passion_category on passion.category_passion=passion_category.idcategorie WHERE name_passion='".strtolower($passion)."' ") or die(mysql_error());
                        $data_verif_new = mysql_num_rows($requete_verif_new);

                        if($data_verif_new != 0 || isset($_POST['mode'])) {

                                        if(isset($_POST['mode']) && $_POST['mode'] == "enregistrericone" && !empty($categorie) && isset($x1) && isset($y1) && isset($x2) && isset($y2) && isset($w) && isset($h) && isset($image) && isset($imageheight) & isset($imagewidth) && $data_verif_new == 0) {
                                                $requete_verif_categorie = mysql_query("SELECT nomcategorie, icone FROM passion_category WHERE idcategorie='".$categorie."'") or die(mysql_error());
                                                $data_verif_categorie = mysql_num_rows($requete_verif_categorie);

                                                if($data_verif_categorie > 0) {
                                                        $extension = pathinfo($reg->appconf['memberdir'].$image, PATHINFO_EXTENSION);
                                                        $nomImageFinal = md5(uniqid()) .'.'. $extension;
                                                        $cropped = resizeThumbnailImage($reg->appconf['memberdir'].$image, "Images/iconespassion/".$nomImageFinal,$w,$h,$x1,$y1,$imageheight,$imagewidth);

                                                        mysql_query("INSERT INTO passion VALUES (default, '".$passion."', '".$categorie."', '".$_SESSION['iddumembre']."', '"."Images/iconespassion/".$nomImageFinal."')") or die(mysql_error());
                                                        $result = "2;".$data_verif_categorie['nomcategorie'].";".stripslashes($passion).";".mysql_insert_id().";Images/iconespassion/".$nomImageFinal;
                                                        return $result;
                                                }
                                        }
                                        else if(isset($_POST['mode']) && $_POST['mode'] == "enregistrergout" && !empty($categorie) && $data_verif_new == 0) {
                                                $requete_verif_categorie = mysql_query("SELECT nomcategorie, icone FROM passion_category WHERE idcategorie='".$categorie."'") or die(mysql_error());
                                                $data_verif_categorie = mysql_num_rows($requete_verif_categorie);

                                                if($data_verif_categorie > 0) {
                                                        mysql_query("INSERT INTO passion VALUES (default, '".$passion."', '".$categorie."', '".$_SESSION['iddumembre']."', default)") or die(mysql_error());
                                                        $result = "2;".$data_verif_categorie['nomcategorie'].";".stripslashes($passion).";".mysql_insert_id().";".$data_verif_categorie['icone'];
                                                        return $result;
                                                }
                                        }
                                        else if($data_verif_new != 0) {
                                                $idpassion = mysql_fetch_array($requete_verif_new);

                                                $result = "2;".$idpassion['nomcategorie'].";".stripslashes($passion).";".$idpassion['id_passion'].";".($idpassion['icone_passion'] == NULL?$idpassion['icone']:$idpassion['icone_passion']);
                                                return $result;
                                        }


                        }
                        else {
                                return "1";
                        }

                }
        }
    }
    
    private function resizeThumbnailImage($image, $thumb_image_name, $w, $h, $start_width, $start_height, $imageheight, $imagewidth){

	$tAttribut = getimagesize($image);
	$extension = strtolower(pathinfo(TARGET.$image, PATHINFO_EXTENSION));
	
	$width = $tAttribut[0];
	$height = $tAttribut[1];
	
	switch($extension) 
	{
		case "jpg":
			$source  = imagecreatefromjpeg($image);  
		break;
		case "jpeg":
			$source  = imagecreatefromjpeg($image);  
		break;
		case "gif":
			$source  = imagecreatefromgif($image);  
		break;
		case "png":
			$source  = imagecreatefrompng($image);  
		break;
	} 
	$w = ($tAttribut[0]/$imagewidth)*$w;
	$h = ($tAttribut[1]/$imageheight)*$h;
	$start_width = ($tAttribut[0]/$imagewidth)*$start_width;
	$start_height = ($tAttribut[1]/$imageheight)*$start_height;
	
	$scale = 75/$w;  		
	$newImageWidth = ceil($w * $scale);
	$newImageHeight = ceil($h * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$w,$h);
	
	switch($extension) 
	{
		case "jpg":
			imagejpeg($newImage,$thumb_image_name);  
		break;
		case "jpeg":
			imagejpeg($newImage,$thumb_image_name);  
		break;
		case "gif":
			imagegif($newImage,$thumb_image_name);  
		break;
		case "png":
			imagepng($newImage,$thumb_image_name);  
		break;
	}
	
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
    }
}

?>
