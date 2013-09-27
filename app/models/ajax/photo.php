<?php

class models_ajax_photo extends Model {

    function registerImage() {

        if (isset($_SESSION['id_member'])) {
            global $reg;

            // Constantes
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
            if (!empty($_FILES['fichier']['name'])) {
                // Recuperation de l'extension du fichier
                $extension = pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION);

                // On verifie l'extension du fichier
                if (in_array(strtolower($extension), $tabExt)) {
                    switch ($_FILES["fichier"]["error"]) {

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
                    $infosImg = getimagesize($_FILES['fichier']['tmp_name']);


                    // On verifie le type de l'image
                    if ($infosImg[2] >= 1 && $infosImg[2] <= 14) {
                        // On verifie les dimensions et taille de l'image
                        if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['fichier']['tmp_name']) <= MAX_SIZE)) {
                            // Parcours du tableau d'erreurs
                            if (isset($_FILES['fichier']['error']) && UPLOAD_ERR_OK === $_FILES['fichier']['error']) {
                                // On renomme le fichier
                                $nomImage = 'temp_image.' . $extension;

                                // Si c'est OK, on teste l'upload
                                if (move_uploaded_file($_FILES['fichier']['tmp_name'], TARGET . $nomImage)) {
                                    $message = '1;';
                                    $message .= "/" . TARGET . $nomImage;
                                    $message .= ";" . $nomImage;

                                    $nomImageFinal = 'temp_image_mini.' . strtolower($extension);

                                    $this->resizeThumbnailPhoto(TARGET . $nomImage, TARGET . $nomImageFinal, 250, 170);
                                    $this->resizeThumbnailPhotoMax(TARGET . $nomImage, TARGET . $nomImage, 500);
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

    private function resizeThumbnailPhotoMax($image, $thumb_image_name, $max) {
        list($width, $height) = getimagesize($image);
        $extension = pathinfo(TARGET . $image, PATHINFO_EXTENSION);

        switch (strtolower($extension)) {
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

        $x = $width;
        $y = $height;
        if ($x > $max or $y > $max) {
            if ($x > $y) {
                $nx = $max;
                $ny = $y / ($x / $max);
            } else {
                $nx = $x / ($y / $max);
                $ny = $max;
            }
        }

        $thumb = imagecreatetruecolor($nx, $ny);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $nx, $ny, $x, $y);

        switch (strtolower($extension)) {
            case "jpg":
                imagejpeg($thumb, $thumb_image_name);
                break;
            case "jpeg":
                imagejpeg($thumb, $thumb_image_name);
                break;
            case "gif":
                imagegif($thumb, $thumb_image_name);
                break;
            case "png":
                imagepng($thumb, $thumb_image_name);
                break;
        }

        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    private function resizeThumbnailPhoto($image, $thumb_image_name, $newSizeL, $newSizeH) {
        list($width, $height) = getimagesize($image);
        $extension = pathinfo(TARGET . $image, PATHINFO_EXTENSION);

        switch (strtolower($extension)) {
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

        $original_aspect = $width / $height;
        $thumb_aspect = $newSizeL / $newSizeH;

        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $newSizeH;
            $new_width = $width / ($height / $newSizeH);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $newSizeL;
            $new_height = $height / ($width / $newSizeL);
        }


        $thumb = imagecreatetruecolor($newSizeL, $newSizeH);
        $cropped = imagecopyresampled($thumb, $source, 0, 0, 0 - ($new_width - $newSizeL) / 2, 0 - ($new_height - $newSizeH) / 2, $new_width, $new_height, $width, $height);

        switch (strtolower($extension)) {
            case "jpg":
                imagejpeg($thumb, $thumb_image_name);
                break;
            case "jpeg":
                imagejpeg($thumb, $thumb_image_name);
                break;
            case "gif":
                imagegif($thumb, $thumb_image_name);
                break;
            case "png":
                imagepng($thumb, $thumb_image_name);
                break;
        }

        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    private function resizeThumbnailImage($image, $thumb_image_name, $w, $h, $start_width, $start_height, $imageheight, $imagewidth) {

        $tAttribut = getimagesize($image);
        $extension = pathinfo(TARGET . $image, PATHINFO_EXTENSION);

        $width = $tAttribut[0];
        $height = $tAttribut[1];

        switch (strtolower($extension)) {
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

        $scale = 150 / $w;
        $scale2 = 150 / $h;
        $newImageWidth = ceil($w * $scale);
        $newImageHeight = ceil($h * $scale2);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $w, $h);

        switch (strtolower($extension)) {
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

    function registerPhoto() {

        if (intval($_SESSION['id_member']) != "") {

            if (isset($_POST['x1']) && isset($_POST['x2']) && isset($_POST['y1']) && isset($_POST['y2']) && isset($_POST['w']) && isset($_POST['h']) && isset($_POST['image']) && isset($_POST['imageheight']) && isset($_POST['imagewidth'])) {
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

            if (isset($x1) && isset($y1) && isset($x2) && isset($y2) && isset($w) && isset($h) && isset($image) && isset($imageheight) & isset($imagewidth)) {
                $extension = pathinfo(TARGET . $image, PATHINFO_EXTENSION);
                $nomImageFinal = md5(uniqid()) . '.' . strtolower($extension);
                $cropped = $this->resizeThumbnailImage(TARGET . $image, TARGET . $nomImageFinal, $w, $h, $x1, $y1, $imageheight, $imagewidth);

                $requete_avatar = mysql_query("SELECT photo_member FROM member WHERE id_member='" . $_SESSION['id_member'] . "'") or die(mysql_error());

                $data_avatar = mysql_fetch_array($requete_avatar);
                $nb_avatar = mysql_num_rows($requete_avatar);

                if ($nb_avatar == 1) {
                    $new_link_image = TARGET . $nomImageFinal;

                    if (!empty($data_avatar['avatar']) && file_exists($data_avatar['avatar']))
                        unlink($data_avatar['avatar']);

                    mysql_query("UPDATE member SET photo_member='" . $new_link_image . "' WHERE id_member='" . $_SESSION['id_member'] . "'") or die(mysql_error());

                    echo "2;" . TARGET . $nomImageFinal;
                }
            }
        }
    }

}

?>
