<?php


/**
 * Description of event
 *
 * @author Deixonne
 */
class models_ajax_event extends Model {
    
    function registerImage() {
        
        if(isset($_SESSION['id_member']))
        {
                global $reg;
                
                // Constantes

                define('MAX_SIZE', 2000000);    // Taille max en octets du fichier
                define('WIDTH_MAX', 2000);    // Largeur max de l'image en pixels
                define('HEIGHT_MAX', 2000);    // Hauteur max de l'image en pixels

                // Tableaux de donnees
                $tabExt = array('jpg','gif','png','jpeg');    // Extensions autorisees
                $infosImg = array();

                // Variables
                $extension = '';
                $message = '';
                $nomImage = '';

                /************************************************************
                 * Creation du repertoire cible si inexistant
                 *************************************************************/
                if( !is_dir(TARGET) ) {
                  if( !mkdir(TARGET, 0777) ) { // Doute sur nom dossier
                        return ('Erreur : le répertoire cible ne peut-être créé ! Vérifiez que vous diposiez des droits suffisants pour le faire ou créez le manuellement !');
                  }
                }

                  // On verifie si le champ est rempli
                  if( !empty($_FILES['photo']['name']) )
                  {
                        // Recuperation de l'extension du fichier
                        $extension  = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

                        // On verifie l'extension du fichier
                        if(in_array(strtolower($extension),$tabExt))
                        { 
                            
                            switch ($_FILES["photo"]["error"])
                            {

                                    case 1 : return ("<p>Erreur : la taille du fichier dépasse le maximum autorisé ".ini_get('upload_max_filesize')."</p>\n");

                                    break;

                                    case 2 : return ("<p>Erreur : la taille du fichier dépasse celle définie dans le formulaire (30Ko).</p>\n");

                                    break;

                                    case 3 : return ("<p>Erreur : Le fichier n'a été que partiellement transmis.</p>\n");

                                    break;

                                    case 4 : return ("<p>Erreur : La transmission n'a pas eu lieu</p>\n");

                                    break;
                            }
                            
                          // On recupere les dimensions du fichier
                          $infosImg = @getimagesize($_FILES['photo']['tmp_name']);


                          // On verifie le type de l'image
                          if($infosImg[2] >= 1 && $infosImg[2] <= 14)
                          {
                                // On verifie les dimensions et taille de l'image
                                if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['photo']['tmp_name']) <= MAX_SIZE))
                                {
                                  // Parcours du tableau d'erreurs
                                  if(isset($_FILES['photo']['error']) 
                                        && UPLOAD_ERR_OK === $_FILES['photo']['error'])
                                  {
                                        // On renomme le fichier
                                        $nomImage = 'temp_iconegout.'. $extension;

                                        // Si c'est OK, on teste l'upload
                                        if(move_uploaded_file($_FILES['photo']['tmp_name'], TARGET.$nomImage))
                                        {
                                          $message = '1;';
                                          $message .= "/".MEMDIR.hash('crc32',crc32(PREFIXE).$_SESSION['id_member'].crc32(SUFFIXE))."/".$nomImage;
                                          $message .= ";".$nomImage;
                                        }
                                        else
                                        {
                                          // Sinon on affiche une erreur systeme
                                          $message = 'Problème lors de l\'upload !';
                                        }
                                  }
                                  else
                                  {
                                        $message = 'Une erreur interne a empêché l\'uplaod de l\'image';
                                  }
                                }
                                else
                                {
                                  // Sinon erreur sur les dimensions et taille de l'image
                                  $message = 'Erreur dans les dimensions de l\'image !';
                                }
                          }
                          else
                          {
                                // Sinon erreur sur le type de l'image
                                $message = 'Le fichier à uploader n\'est pas une image !';
                          }
                        }
                        else
                        {
                          // Sinon on affiche une erreur pour l'extension
                          $message = 'L\'extension du fichier est incorrecte !';
                        }
                  }
                  else
                  {
                        // Sinon on affiche une erreur pour le champ vide
                        $message = 'Veuillez remplir le formulaire svp !';
                  }

                return $message;
        }
        else
        {
                return "Vous n'êtes pas connecté";
        }
    }
    
    function addEvent() {
        global $reg;

        $erreur="";
        if(isset($_POST['x1']) && isset($_POST['x2']) && isset($_POST['y1']) && isset($_POST['y2']) && isset($_POST['w']) && isset($_POST['h']) && isset($_POST['image']) && isset($_POST['imageheight']) && isset($_POST['imagewidth'])) {
                $x1 = intval($_POST['x1']);
                $y1 = intval($_POST['y1']);
                $x2 = intval($_POST['x2']);
                $y2 = intval($_POST['y2']);
                $w = intval($_POST['w']);
                $h = intval($_POST['h']);
                $image = $reg->clean->POST('image');
                $imageheight = intval($_POST['imageheight']);
                $imagewidth = intval($_POST['imagewidth']);
        }
        else
                $erreur .= "- Veuillez choisir une image de couverture et la redimensionner<br/>";

        if(isset($_POST['subject']) && $_POST['subject'] != "" && $_POST['subject'] != "Saisissez le nom de l'évènement")
                $subject = $reg->clean->POST('subject');
        else
                $erreur .= "- Veuillez saisir le nom de l'évènement<br/>";

        if(isset($_POST['content_happends']) && $_POST['content_happends'] != "" && $_POST['content_happends'] != "Description...")
                $content_happends = $reg->clean->POST('content_happends');
        else
                $erreur .= "- Veuillez saisir une description<br/>";

        if(isset($_POST['pays']) && $_POST['pays'] != "")
                $pays = $_POST['pays'];
        else
                $erreur .= "- Veuillez sélectionner un pays<br/>";

        if(isset($_POST['cp']) && isset($_POST['ville']) && $_POST['cp'] != "" && $_POST['ville'] != "" && is_numeric($_POST['cp']) && $_POST['cp'] != "- Veuillez saisir un code postal<br/>") {
                $cp = $_POST['cp'];
                $ville = $reg->clean->POST('ville');
                $resultat_cp = mysql_query("SELECT CP from zip_code WHERE CP = '". $cp ."' AND Ville = '".$ville."'") or die(mysql_error());
                if(mysql_num_rows($resultat_cp) == 0)
                        $erreur .= "- Veuillez saisir un code postal valide";
        }
        else
                $erreur .= "- Veuillez saisir un code postal<br/>";

        if(isset($_POST['lieu']) && $_POST['lieu'] != "" && $_POST['lieu'] != "Saisissez une adresse ou un lieu")
                $lieu = $reg->clean->POST('lieu');
        else
                $erreur .= "- Veuillez saisir un lieu<br/>";

        if(isset($_POST['event_confidentialite_info']) && ($_POST['event_confidentialite_info'] == 1 || $_POST['event_confidentialite_info'] == 2 || $_POST['event_confidentialite_info'] == 0))
                $event_confidentialite_info = $_POST['event_confidentialite_info'];
        else
                $erreur .= "- Veuillez vérifier les paramètres de confidentialité<br/>";

        if(isset($_POST['event_confidentialite_info']) && $_POST['event_confidentialite_info'] == 2 && $_POST['membre_confidentialite'] != "")
                $membre_confidentialite = $_POST['membre_confidentialite'];
        else if(isset($_POST['event_confidentialite_info']) && $_POST['event_confidentialite_info'] == 2 && $_POST['membre_confidentialite'] == "")
                $erreur .= "- Vous devez ajouter les contacts autorisés à voir votre évènement privé<br/>";

        if(isset($_POST['passion']) && $_POST['passion'] != "")
                $passion = $_POST['passion'];
        else
                $erreur .= "- Vous devez lier 2 mots clefs/passions minimum<br/>";

        if(isset($_POST['date_debut']) && isset($_POST['date_fin']) && is_numeric($_POST['date_fin']) && is_numeric($_POST['date_debut']) && $_POST['date_fin'] - $_POST['date_debut'] >= 0) {
                $date_debut = $_POST['date_debut'];
                $date_fin = $_POST['date_fin'];
        }
        else
                $erreur .= "- Vous devez saisir une date début antérieure à la date de fin<br/>";

        if(isset($_POST['time_option2']) && $_POST['time_option2'] == 'checked')
                $time_option_debut = 1;
        else
                $time_option_debut = 0;

        if(isset($_POST['time_option1']) && $_POST['time_option1'] == 'checked')
                $time_option_fin = 1;
        else
                $time_option_fin = 0;

        if(!empty($erreur))
                echo "<h2>Erreur</h2>".$erreur;
        else {
                if($event_confidentialite_info == 2) {
                        $split_membre_confidentialite = explode(";",$membre_confidentialite);

                        $nb_confidentialite=0;
                        $tab_membre_confidentialite = array();
                        foreach($split_membre_confidentialite as $t_split_membre_confidentialite)
                                if(is_numeric($t_split_membre_confidentialite) && !in_array($t_split_membre_confidentialite,$tab_membre_confidentialite)) { $nb_confidentialite++; $tab_membre_confidentialite[] = $t_split_membre_confidentialite; }

                        if(sizeof($tab_membre_confidentialite) > 0 && $nb_confidentialite > 0) {
                                $requete_verif_confidentialite = mysql_num_rows(mysql_query("SELECT id_member FROM member WHERE id_member IN (".implode(",", $tab_membre_confidentialite).")"));

                                if($requete_verif_confidentialite != $nb_confidentialite)
                                        $erreur .= "- Vous devez autoriser des contacts valides à votre évènement<br/>";
                        }
                        else
                                $erreur .= "- Vous devez autoriser des contacts valides à votre évènement<br/>";
                }

                $split_passion = explode(";", $passion);
                $nb_passion=0;
                $tab_passion = array();
                foreach($split_passion as $t_split_passion)
                        if(is_numeric($t_split_passion) && !in_array($t_split_passion,$tab_passion)) { $nb_passion++; $tab_passion[] = $t_split_passion; }

                if(sizeof($tab_passion) >= 1 && $nb_passion >= 1) {
                        $requete_verif_passion= mysql_num_rows(mysql_query("SELECT id_passion FROM passion WHERE id_passion IN (".implode(",", $tab_passion).")"));

                        if($requete_verif_passion != $nb_passion)
                                $erreur .= "- Vous devez ajouter 1 mot clef/passion valides<br/>";
                }
                else
                        $erreur .= "- Vous devez ajouter 1 mot clef/passion valide au minimum<br/>";

                if(!empty($erreur))
                        echo "<h2>Erreur</h2>".$erreur;	
                else {
                        $extension = pathinfo(TARGET.$image, PATHINFO_EXTENSION);
                        $nomImageFinal = md5(uniqid()) .'.'. $extension;
                        $cropped = $this->resizeThumbnailImage(TARGET.$image, TARGET.$nomImageFinal,$w,$h,$x1,$y1,$imageheight,$imagewidth);

                        $requete_map = mysql_query("SELECT longitude, latitude FROM zip_code WHERE CP='".$cp."' AND ville='".$ville."'") or die(mysql_error());
                        $data_map = mysql_fetch_array($requete_map);
                        $nb_data_map = mysql_num_rows($requete_map);

                        if($nb_data_map == 1) {
                                $lat = $data_map['latitude'];
                                $lon = $data_map['longitude'];
                        }
                        else {
                                $lat = "";
                                $lon = "";
                        }

                        mysql_query("INSERT INTO event VALUES (default,'".$subject."','".$content_happends."','".TARGET.$nomImageFinal."',0,".$_SESSION['id_member'].",'".date("Y-m-j H:i:s",ceil($date_debut/1000))."','".date("Y-m-j H:i:s",ceil($date_fin/1000))."',".$cp.",'".$ville."','".$pays."','".$lieu."',".$event_confidentialite_info.",'".$lon."','".$lat."',".$time_option_debut.",".$time_option_fin.")") or die(mysql_error());
                        $id_event = mysql_insert_id();

                        if($event_confidentialite_info == 2) {
                                $values_insert_confidentialite="";
                                $cp=0;
                                foreach($tab_membre_confidentialite as $t_tab_membre_confidentialite) {
                                        $values_insert_confidentialite .= ($cp>0?",":"")."(".$id_event.",".$t_tab_membre_confidentialite.")";
                                        $cp++;
                                }
                                mysql_query("INSERT INTO event_confidentiality VALUES ".$values_insert_confidentialite."") or die(mysql_error());
                        }

                        $values_insert_passion="";
                        $cp=0;
                        foreach($tab_passion as $t_tab_passion) {
                                $values_insert_passion .= ($cp>0?",":"")."(".$id_event.",".$t_tab_passion.")";
                                $cp++;
                        }

                        mysql_query("INSERT INTO event_passion VALUES ".$values_insert_passion."") or die(mysql_error());
                }
        }
    }
    
    private function resizeThumbnailImage($image, $thumb_image_name, $w, $h, $start_width, $start_height, $imageheight, $imagewidth){

                $tAttribut = getimagesize($image);
                $extension = pathinfo(TARGET.$image, PATHINFO_EXTENSION);

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

                $scale_w = 275/$w;  		
                $scale_h = 137/$h;

                $newImageWidth = ceil($w * $scale_w);
                $newImageHeight = ceil($h * $scale_h);
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
