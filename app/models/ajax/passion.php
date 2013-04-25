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

        $q = $reg->clean->GET('q');
        $result = "";

        if (!empty($q)) {
            $resultat = mysql_query("SELECT id_passion,name_passion,icon_passion AS icone,passion_category.icon_passion AS icone_categorie,nomcategorie from passion_categorie inner join passion_category on passion_categorie.idcategorie=passion_category.category_passion WHERE name_passion LIKE '%" . $q . "%' LIMIT 11") or die(mysql_error());
            while ($donnees = mysql_fetch_assoc($resultat)) {
                $result .= ucfirst($donnees['name_passion']) . "|" . ucfirst($donnees['name_category']) . "|" . (!empty($donnees['icone']) ? $donnees['icone'] : $donnees['icone_categorie']) . "\n";
            }

            if (mysql_num_rows($resultat) == 0) {
                $result = "Aucun résultat | |\n";
            }
        }

        return $result;
    }

    function categoryPassion() {

        if (isset($_GET['mode']) && $_GET['mode'] == "categorie") {
            $requete = mysql_query("SELECT name_category,id_category,icone FROM passion_category ORDER BY name_category ASC") or die(mysql_error());

            $result = "";
            while ($data = mysql_fetch_array($requete))
                $result .= "<li value='" . $data['id_category'] . "'><img src='" . $data['icone'] . "' alt='" . $data['name_category'] . "' />" . ucfirst($data['name_category']) . "</li>";

            return $result;
        }
    }

    function registerImage() {

        if (isset($_SESSION['id_member'])) {
            global $reg;

            // Constantes
            define('TARGET', MEMDIR . hash('crc32', crc32(PREFIXE) . $_SESSION['id_member'] . crc32(SUFFIXE)) . '/');    // Repertoire cible
            define('MAX_SIZE', 2000000);    // Taille max en octets du fichier
            define('WIDTH_MAX', 2000);    // Largeur max de l'image en pixels
            define('HEIGHT_MAX', 2000);    // Hauteur max de l'image en pixels
            // Tableaux de donnees
            $tabExt = array('jpg', 'gif', 'png', 'jpeg');    // Extensions autorisees
            $infosImg = array();

            // Variables
            $extension = '';
            $message = '';
            $nomImage = '';

            /*             * **********************************************************
             * Creation du repertoire cible si inexistant
             * *********************************************************** */
            if (!is_dir(TARGET)) {
                if (!mkdir(TARGET, 0777)) { // Doute sur nom dossier
                    return ('Erreur : le répertoire cible ne peut-être créé ! Vérifiez que vous diposiez des droits suffisants pour le faire ou créez le manuellement !');
                }
            }

            // On verifie si le champ est rempli
            if (!empty($_FILES['filepassion']['name'])) {
                // Recuperation de l'extension du fichier
                $extension = pathinfo($_FILES['filepassion']['name'], PATHINFO_EXTENSION);

                // On verifie l'extension du fichier
                if (in_array(strtolower($extension), $tabExt)) {
                    switch ($_FILES["photo"]["error"]) {

                        case 1 : return ("<p>Erreur : la taille du fichier dépasse le maximum autorisé " . ini_get('upload_max_filesize') . "</p>\n");

                            break;

                        case 2 : return ("<p>Erreur : la taille du fichier dépasse celle définie dans le formulaire (30Ko).</p>\n");

                            break;

                        case 3 : return ("<p>Erreur : Le fichier n'a été que partiellement transmis.</p>\n");

                            break;

                        case 4 : return ("<p>Erreur : La transmission n'a pas eu lieu</p>\n");

                            break;
                    }

                    // On recupere les dimensions du fichier
                    $infosImg = getimagesize($_FILES['filepassion']['tmp_name']);


                    // On verifie le type de l'image
                    if ($infosImg[2] >= 1 && $infosImg[2] <= 14) {
                        // On verifie les dimensions et taille de l'image
                        if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['filepassion']['tmp_name']) <= MAX_SIZE)) {
                            // Parcours du tableau d'erreurs
                            if (isset($_FILES['filepassion']['error']) && UPLOAD_ERR_OK === $_FILES['filepassion']['error']) {
                                // On renomme le fichier
                                $nomImage = 'temp_iconegout.' . $extension;

                                // Si c'est OK, on teste l'upload
                                if (move_uploaded_file($_FILES['filepassion']['tmp_name'], TARGET . $nomImage)) {
                                    $message = '1;';
                                    $message .= MEMDIR . hash('crc32', crc32(PREFIXE) . $_SESSION['id_member'] . crc32(SUFFIXE)) . "/" . $nomImage;
                                    $message .= ";" . $nomImage;
                                } else {
                                    // Sinon on affiche une erreur systeme
                                    $message = 'Problème lors de l\'upload !';
                                }
                            } else {
                                $message = 'Une erreur interne a empêché l\'uplaod de l\'image';
                            }
                        } else {
                            // Sinon erreur sur les dimensions et taille de l'image
                            $message = 'Erreur dans les dimensions de l\'image !';
                        }
                    } else {
                        // Sinon erreur sur le type de l'image
                        $message = 'Le fichier à uploader n\'est pas une image !';
                    }
                } else {
                    // Sinon on affiche une erreur pour l'extension
                    $message = 'L\'extension du fichier est incorrecte !';
                }
            } else {
                // Sinon on affiche une erreur pour le champ vide
                $message = 'Veuillez remplir le formulaire svp !';
            }

            return $message;
        } else {
            return "Vous n'êtes pas connecté";
        }
    }

    function registerPassion() {

        global $reg;

        define('TARGET', MEMDIR . hash('crc32', crc32(PREFIXE) . $_SESSION['id_member'] . crc32(SUFFIXE)) . '/');    // Repertoire cible

        $result = "";

        if ($_SESSION['id_member'] != "") {

            $passion = $reg->clean->POST('passion');
            $categorie = intval($_POST['categorie']);

            if (isset($_POST['x1']) && isset($_POST['x2']) && isset($_POST['y1']) && isset($_POST['y2']) && isset($_POST['w']) && isset($_POST['h']) && isset($_POST['image']) && isset($_POST['imageheight']) && isset($_POST['imagewidth'])) {
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

            if (!empty($passion)) {
                $requete_verif_new = mysql_query("SELECT passion.id_passion,name_passion,name_category,passion_category.icone,passion.icon_passion AS icone_passion FROM passion inner join passion_category on passion.id_category=passion_category.id_category WHERE name_passion='" . strtolower($passion) . "' ") or die(mysql_error());
                $data_verif_new = mysql_num_rows($requete_verif_new);

                if ($data_verif_new != 0 || isset($_POST['mode'])) {

                    if (isset($_POST['mode']) && $_POST['mode'] == "enregistrericone" && !empty($categorie) && isset($x1) && isset($y1) && isset($x2) && isset($y2) && isset($w) && isset($h) && isset($image) && isset($imageheight) & isset($imagewidth) && $data_verif_new == 0) {
                        $requete_verif_categorie = mysql_query("SELECT name_category, icone FROM passion_category WHERE id_category='" . $categorie . "'") or die(mysql_error());
                        $data_verif_categorie = mysql_num_rows($requete_verif_categorie);

                        if ($data_verif_categorie > 0) {
                            $extension = pathinfo($image, PATHINFO_EXTENSION);
                            $nomImageFinal = md5(uniqid()) . '.' . $extension;
                            $cropped = $this->resizeThumbnailImage(TARGET . $image, "Images/iconespassion/" . $nomImageFinal, $w, $h, $x1, $y1, $imageheight, $imagewidth);

                            mysql_query("INSERT INTO passion VALUES (default, '" . $passion . "', '" . $categorie . "', '" . $_SESSION['id_member'] . "', '" . "Images/iconespassion/" . $nomImageFinal . "')") or die(mysql_error());
                            $result = "2;" . $data_verif_categorie['nomcategorie'] . ";" . stripslashes($passion) . ";" . mysql_insert_id() . ";Images/iconespassion/" . $nomImageFinal;
                            return $result;
                        }
                    } else if (isset($_POST['mode']) && $_POST['mode'] == "enregistrergout" && !empty($categorie) && $data_verif_new == 0) {
                        $requete_verif_categorie = mysql_query("SELECT name_category, icone FROM passion_category WHERE id_category='" . $categorie . "'") or die(mysql_error());
                        $data_verif_categorie = mysql_num_rows($requete_verif_categorie);

                        if ($data_verif_categorie > 0) {
                            mysql_query("INSERT INTO passion VALUES (default, '" . $passion . "', '" . $categorie . "', '" . $_SESSION['id_member'] . "', default)") or die(mysql_error());
                            $result = "2;" . $data_verif_categorie['name_category'] . ";" . stripslashes($passion) . ";" . mysql_insert_id() . ";" . $data_verif_categorie['icone'];
                            return $result;
                        }
                    } else if ($data_verif_new != 0) {
                        $idpassion = mysql_fetch_array($requete_verif_new);

                        $result = "2;" . $idpassion['name_category'] . ";" . stripslashes($passion) . ";" . $idpassion['id_passion'] . ";" . ($idpassion['icone_passion'] == NULL ? $idpassion['icone'] : $idpassion['icone_passion']);
                        return $result;
                    }
                } else {
                    return "1";
                }
            }
        }
    }

    private function resizeThumbnailImage($image, $thumb_image_name, $w, $h, $start_width, $start_height, $imageheight, $imagewidth) {

        $tAttribut = getimagesize($image);
        $extension = strtolower(pathinfo(TARGET . $image, PATHINFO_EXTENSION));

        $width = $tAttribut[0];
        $height = $tAttribut[1];

        switch ($extension) {
            case "jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "jpeg":
                $source = imagecreatefromjpeg($image);
                break;
            case "gif":
                $source = imagecreatefromgif($image);
                break;
            case "png":
                $source = imagecreatefrompng($image);
                break;
        }
        $w = ($tAttribut[0] / $imagewidth) * $w;
        $h = ($tAttribut[1] / $imageheight) * $h;
        $start_width = ($tAttribut[0] / $imagewidth) * $start_width;
        $start_height = ($tAttribut[1] / $imageheight) * $start_height;

        $scale = 75 / $w;
        $newImageWidth = ceil($w * $scale);
        $newImageHeight = ceil($h * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $w, $h);

        switch ($extension) {
            case "jpg":
                imagejpeg($newImage, $thumb_image_name);
                break;
            case "jpeg":
                imagejpeg($newImage, $thumb_image_name);
                break;
            case "gif":
                imagegif($newImage, $thumb_image_name);
                break;
            case "png":
                imagepng($newImage, $thumb_image_name);
                break;
        }

        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

}

?>
