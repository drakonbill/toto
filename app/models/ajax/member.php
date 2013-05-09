<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of member
 *
 * @author Deixonne
 */
class models_ajax_member extends Model {
    
     function verifMember () {
         $pseudomembre = strtolower($_POST['pseudomembre']);

        if(!empty($pseudomembre)) {
                $requete_verif_membre = mysql_query("SELECT id_member FROM member WHERE LOWER(pseudo_member)='".$pseudomembre."'") or die(mysql_error());
                $data_verif_membre = mysql_fetch_array($requete_verif_membre);

                if(!empty($data_verif_membre))
                        return $data_verif_membre[0];
                else
                        return "notok";

        }
        else {
                return "notok";
        }
     }
     
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
                  if( !empty($_FILES['fichier']['name']) )
                  {
                        // Recuperation de l'extension du fichier
                        $extension  = pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION);

                        // On verifie l'extension du fichier
                        if(in_array(strtolower($extension),$tabExt))
                        {
                           switch ($_FILES["fichier"]["error"])
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
                          $infosImg = getimagesize($_FILES['fichier']['tmp_name']);


                          // On verifie le type de l'image
                          if($infosImg[2] >= 1 && $infosImg[2] <= 14)
                          {
                                // On verifie les dimensions et taille de l'image
                                if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['fichier']['tmp_name']) <= MAX_SIZE))
                                {
                                  // Parcours du tableau d'erreurs
                                  if(isset($_FILES['fichier']['error']) 
                                        && UPLOAD_ERR_OK === $_FILES['fichier']['error'])
                                  {
                                        // On renomme le fichier
                                        $nomImage = 'temp_image.'. $extension;

                                        // Si c'est OK, on teste l'upload
                                        if(move_uploaded_file($_FILES['fichier']['tmp_name'], TARGET.$nomImage))
                                        {
                                          $message = '1;';
                                          $message .= "/".TARGET.$nomImage;
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
    
    private function resizeThumbnailImage($image, $thumb_image_name, $w, $h, $start_width, $start_height, $imageheight, $imagewidth){

	$tAttribut = getimagesize($image);
	$extension = pathinfo(TARGET.$image, PATHINFO_EXTENSION);
	
	$width = $tAttribut[0];
	$height = $tAttribut[1];

	switch(strtolower($extension)) 
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
	
	$scale = 150/$w; 
	$scale2 = 150/$h;
	$newImageWidth = ceil($w * $scale);
	$newImageHeight = ceil($h * $scale2);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$w,$h);
	
	switch(strtolower($extension)) 
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

    function registerImageProfil() {
        
        if(intval($_SESSION['id_member']) != "")
        {

                if(isset($_POST['x1']) && isset($_POST['x2']) && isset($_POST['y1']) && isset($_POST['y2']) && isset($_POST['w']) && isset($_POST['h']) && isset($_POST['image']) && isset($_POST['imageheight']) && isset($_POST['imagewidth'])) {
                        $x1 = intval($_POST['x1']);
                        $y1 = intval($_POST['y1']);
                        $x2 = intval($_POST['x2']);
                        $y2 = intval($_POST['y2']);
                        $w = intval($_POST['w']);
                        $h = intval($_POST['h']);
                        $image = trim($_POST['image']);
                        $imageheight = intval($_POST['imageheight']);
                        $imagewidth = intval($_POST['imagewidth']);
                }

                if(isset($x1) && isset($y1) && isset($x2) && isset($y2) && isset($w) && isset($h) && isset($image) && isset($imageheight) & isset($imagewidth)) {
                        $extension = pathinfo(TARGET.$image, PATHINFO_EXTENSION);
                        $nomImageFinal = md5(uniqid()) .'.'. strtolower($extension);
                        $cropped = $this->resizeThumbnailImage(TARGET.$image, TARGET.$nomImageFinal,$w,$h,$x1,$y1,$imageheight,$imagewidth);

                        $requete_avatar = mysql_query("SELECT photo_member FROM member WHERE id_member='".$_SESSION['id_member']."'") or die(mysql_error());

                        $data_avatar = mysql_fetch_array($requete_avatar);
                        $nb_avatar = mysql_num_rows($requete_avatar);

                        if($nb_avatar == 1) {
                                $new_link_image = TARGET.$nomImageFinal;

                               /* $con = new Mongo();

                                $db = $con->bdd;

                                $Collection_members = $db->members;
                                $Collection_friends = $db->friends;
                                $Collection_messages = $db->messages;
                                $Collection_conversations = $db->conversations;
                                $Collection_invitations = $db->invitations;
                                $Collection_groupes = $db->groupes;
                                $Collection_invitationgroupes = $db->invitationgroupes;
                                $Collection_messagegroupes = $db->messagegroupes;
                                $Collection_rooms = $db->rooms;
                                $Collection_messagerooms = $db->messagerooms;
                                $Collection_bloquerooms = $db->bloquerooms;
                                $Collection_invitationrooms = $db->invitationrooms;

                                $Collection_members->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
                                $Collection_friends->update(array('idfriend'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
                                $Collection_messages->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
                                $Collection_conversations->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
                                $Collection_invitations->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
                                $Collection_invitations->update(array('idfriend'=>$_SESSION['iddumembre']), array('$set'=>array('avatarfriend'=>$new_link_image)), array('multiple'=>true));

                                $Collection_groupes->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
                                $Collection_invitationgroupes->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
                                $Collection_invitationgroupes->update(array('idfriend'=>$_SESSION['iddumembre']), array('$set'=>array('avatarfriend'=>$new_link_image)), array('multiple'=>true));
                                $Collection_messagegroupes->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));

                                $Collection_rooms->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
                                $Collection_messagerooms->update(array('idmembre'=>$_SESSION['iddumembre']), array('$set'=>array('avatar'=>$new_link_image)), array('multiple'=>true));
*/
                                if(!empty($data_avatar['avatar']) && file_exists($data_avatar['avatar']))
                                        unlink($data_avatar['avatar']);

                                mysql_query("UPDATE member SET photo_member='".$new_link_image."' WHERE id_member='".$_SESSION['id_member']."'") or die(mysql_error());

                                echo "2;".TARGET.$nomImageFinal;
                        }

                }

        }
    }
    
    function addContact() {
        $iddumembre = $_POST['iddumembre'];
        $idmoi = $_SESSION['id_member'];
        $action = $_POST['action'];

        if(!empty($iddumembre) && !empty($idmoi) && is_numeric($iddumembre) && is_numeric($idmoi))
        {

                        $resultat = mysql_query("SELECT * FROM member_contacts WHERE id_contact=".$iddumembre." AND id_member=".$idmoi." OR id_contact=".$idmoi." AND id_member=".$iddumembre."");

                        while($data = mysql_fetch_assoc($resultat))
                        {
                                $etat[$data['id_member']] = $data['condition_contact'];
                        }

                        if(empty($etat) && $action == 1)
                        {
                                mysql_query("INSERT INTO member_contacts VALUES (".$iddumembre.",".$idmoi.",default,default)") or die(mysql_error());
                                mysql_query("INSERT INTO member_contacts VALUES (".$idmoi.",".$iddumembre.",default,1)") or die(mysql_error());
                                return "1";
                        }
                        else if($etat[$idmoi] == 0 && $etat[$iddumembre] == 1 && $action == 1)
                        {
                                /*try {
                                        $con = new Mongo();
                                        $db = $con->bdd;

                                        $Collection_friends = $db->friends;

                                        $new_friend = array(	'pseudo'=>$etat[$iddumembre]['pseudo'],
                                                                                        'avatar'=>$etat[$iddumembre]['avatar'],
                                                                                        'date'=>(int)time(),
                                                                                        'idmembre'=>$_SESSION['iddumembre'],
                                                                                        'idfriend'=>$iddumembre,
                                                                                        'autre'=>false,
                                                                                        'status'=>1
                                                                                );

                                        $new_friend2 = array(	'pseudo'=>$etat[$iddumembre]['pseudo'],
                                                                                        'avatar'=>$etat[$iddumembre]['avatar'],
                                                                                        'date'=>(int)time(),
                                                                                        'idmembre'=>$iddumembre,
                                                                                        'idfriend'=>$_SESSION['iddumembre'],
                                                                                        'autre'=>false,
                                                                                        'status'=>1
                                                                                );

                                        $Collection_friends->insert($new_friend, array('fsync' => true));
                                        $Collection_friends->insert($new_friend2, array('fsync' => true));
                                }
                                catch(Exception $e) {
                                        $error_mongodb = "Erreur de chargement";
                                }*/

                                mysql_query("UPDATE member_contacts SET condition_contact=1 WHERE id_contact=".$iddumembre." AND id_member=".$idmoi."");
                                return "2";
                        }
                        else if($etat[$idmoi] == 1 && $etat[$iddumembre] == 1 && $action == 2)
                        {
                                /*try {
                                        $con = new Mongo();
                                        $db = $con->bdd;

                                        $Collection_friends = $db->friends;

                                        $friends = $Collection_friends->find(array('$or'=>array(0=>array('idmembre'=>$idmoi,'idfriend'=>$iddumembre),1=>array('idmembre'=>$iddumembre,'idfriend'=>$idmoi))));

                                        if($friends->count() == 2)
                                                $Collection_friends->remove(array('$or'=>array(0=>array('idmembre'=>$idmoi,'idfriend'=>$iddumembre),1=>array('idmembre'=>$iddumembre,'idfriend'=>$idmoi))));
                                }
                                catch(Exception $e) {
                                        $error_mongodb = "Erreur de chargement";
                                }*/

                                mysql_query("DELETE FROM member_contacts WHERE id_contact=".$iddumembre." AND id_member=".$idmoi." OR id_contact=".$idmoi." AND id_member=".$iddumembre."");
                                return "2";
                        }
                        else if($etat[$idmoi] == 1 && $etat[$iddumembre] == 0 && $action == 3)
                        {
                                mysql_query("DELETE FROM member_contacts WHERE id_contact=".$iddumembre." AND id_member=".$idmoi." OR id_contact=".$idmoi." AND id_member=".$iddumembre."");
                                return "3";
                        }
                        else if($etat[$idmoi] == 0 && $etat[$iddumembre] == 1 && $action == 4)
                        {
                                mysql_query("DELETE FROM member_contacts WHERE id_contact=".$iddumembre." AND id_member=".$idmoi." OR id_contact=".$idmoi." AND id_member=".$iddumembre."");
                                return "4";
                        }
        }
    }
     
     
}

?>
