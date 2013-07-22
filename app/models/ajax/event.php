<?php

/**
 * Description of event
 *
 * @author Deixonne
 */
class models_ajax_event extends Model {

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
            if (!empty($_FILES['photo']['name'])) {
                // Recuperation de l'extension du fichier
                $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

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
                    $infosImg = @getimagesize($_FILES['photo']['tmp_name']);


                    // On verifie le type de l'image
                    if ($infosImg[2] >= 1 && $infosImg[2] <= 14) {
                        // On verifie les dimensions et taille de l'image
                        if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['photo']['tmp_name']) <= MAX_SIZE)) {
                            // Parcours du tableau d'erreurs
                            if (isset($_FILES['photo']['error']) && UPLOAD_ERR_OK === $_FILES['photo']['error']) {
                                // On renomme le fichier
                                $nomImage = 'temp_iconegout.' . $extension;

                                // Si c'est OK, on teste l'upload
                                if (move_uploaded_file($_FILES['photo']['tmp_name'], TARGET . $nomImage)) {
                                    $message = '1;';
                                    $message .= "/" . MEMDIR . hash('crc32', crc32(PREFIXE) . $_SESSION['id_member'] . crc32(SUFFIXE)) . "/" . $nomImage;
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

    function addEvent() {
        global $reg;

        $erreur = "";


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
        else
            $erreur .= "- Veuillez choisir une image de couverture et la redimensionner<br/>";

        if (isset($_POST['subject']) && $_POST['subject'] != "" && $_POST['subject'] != "Saisissez le nom de l'évènement")
            $subject = $reg->clean->POST('subject');
        else
            $erreur .= "- Veuillez saisir le nom de l'évènement<br/>";

        if (isset($_POST['content_happends']) && $_POST['content_happends'] != "" && $_POST['content_happends'] != "Description...")
            $content_happends = $reg->clean->POST('content_happends');
        else
            $erreur .= "- Veuillez saisir une description<br/>";

        if (isset($_POST['lon']) && isset($_POST['lat'])) {
            $r_google = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $_POST['lat'] . "," . $_POST['lon'] . "&sensor=false";
            $return_google = file_get_contents($r_google);
            $data_google = json_decode($return_google);

            if ($data_google->status == 'OK') {
                $country = "";
                $area = "";
                $address = "";
                $lat = $_POST['lat'];
                $lon = $_POST['lon'];
                foreach ($data_google->results[0]->address_components as $val) {
                    if ($val->types[0] == 'country')
                        $country = $val->long_name;
                    else if ($val->types[0] == 'administrative_area_level_1')
                        $area = $val->long_name;
                }
                $address = $data_google->results[0]->formatted_address;

                if (empty($country) || empty($area) || empty($address))
                    $erreur .= "- Le lieu que vous avez indiqué manque de précision<br/>";
            }
            else if ($data_google['status'] == 'ZERO_RESULTS')
                $erreur .= "- Il n'y a pas de résultats correspondant à votre lieu<br/>";
            else
                $erreur .= "- Un problème est survenu lors la récupération des données du lieu, réessayer ou contactez-nous si le problème persiste (" . $decode_google->status . ")<br/>";
        }
        else
            $erreur .= "- Veuillez saisir la localisation de votre évènement<br/>";

        if (isset($_POST['event_confidentialite_info']) && ($_POST['event_confidentialite_info'] == 1 || $_POST['event_confidentialite_info'] == 2 || $_POST['event_confidentialite_info'] == 0))
            $event_confidentialite_info = $_POST['event_confidentialite_info'];
        else
            $erreur .= "- Veuillez vérifier les paramètres de confidentialité<br/>";

        if (isset($_POST['event_confidentialite_info']) && $_POST['event_confidentialite_info'] == 2 && $_POST['membre_confidentialite'] != "")
            $membre_confidentialite = $_POST['membre_confidentialite'];
        else if (isset($_POST['event_confidentialite_info']) && $_POST['event_confidentialite_info'] == 2 && $_POST['membre_confidentialite'] == "")
            $erreur .= "- Vous devez ajouter les contacts autorisés à voir votre évènement privé<br/>";

        $nblimit = 0;
        if (isset($_POST['nblimit']) && !is_numeric($_POST['nblimit']))
            $erreur .= "- Veuillez saisir un nombre pour la limite de places";
        else if (isset($_POST['nblimit']))
            $nblimit = $_POST['nblimit'];

        if (isset($_POST['passion']) && $_POST['passion'] != "")
            $passion = $_POST['passion'];
        else
            $erreur .= "- Vous devez lier 2 mots clefs/passions minimum<br/>";

        if (isset($_POST['date_debut']) && isset($_POST['date_fin']) && is_numeric($_POST['date_fin']) && is_numeric($_POST['date_debut']) && $_POST['date_fin'] - $_POST['date_debut'] >= 0) {
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
        }
        else
            $erreur .= "- Vous devez saisir une date début antérieure à la date de fin<br/>";

        if (isset($_POST['time_option2']) && $_POST['time_option2'] == 'checked')
            $time_option_debut = 1;
        else
            $time_option_debut = 0;

        if (isset($_POST['time_option1']) && $_POST['time_option1'] == 'checked')
            $time_option_fin = 1;
        else
            $time_option_fin = 0;

        if (!empty($erreur))
            echo "<h2>Erreur</h2>" . $erreur;
        else {
            if ($event_confidentialite_info == 2) {
                $split_membre_confidentialite = explode(";", $membre_confidentialite);

                $nb_confidentialite = 0;
                $tab_membre_confidentialite = array();
                foreach ($split_membre_confidentialite as $t_split_membre_confidentialite)
                    if (is_numeric($t_split_membre_confidentialite) && !in_array($t_split_membre_confidentialite, $tab_membre_confidentialite)) {
                        $nb_confidentialite++;
                        $tab_membre_confidentialite[] = $t_split_membre_confidentialite;
                    }

                if (sizeof($tab_membre_confidentialite) > 0 && $nb_confidentialite > 0) {
                    $requete_verif_confidentialite = mysql_num_rows(mysql_query("SELECT id_member FROM member WHERE id_member IN (" . implode(",", $tab_membre_confidentialite) . ")"));

                    if ($requete_verif_confidentialite != $nb_confidentialite)
                        $erreur .= "- Vous devez autoriser des contacts valides à votre évènement<br/>";
                }
                else
                    $erreur .= "- Vous devez autoriser des contacts valides à votre évènement<br/>";
            }

            $split_passion = explode(";", $passion);
            $nb_passion = 0;
            $tab_passion = array();
            foreach ($split_passion as $t_split_passion)
                if (is_numeric($t_split_passion) && !in_array($t_split_passion, $tab_passion)) {
                    $nb_passion++;
                    $tab_passion[] = $t_split_passion;
                }

            if (sizeof($tab_passion) >= 1 && $nb_passion >= 1) {
                $requete_verif_passion = mysql_num_rows(mysql_query("SELECT id_passion FROM passion WHERE id_passion IN (" . implode(",", $tab_passion) . ")"));

                if ($requete_verif_passion != $nb_passion)
                    $erreur .= "- Vous devez ajouter 1 mot clef/passion valides<br/>";
            }
            else
                $erreur .= "- Vous devez ajouter 1 mot clef/passion valide au minimum<br/>";

            if (!empty($erreur))
                echo "<h2>Erreur</h2>" . $erreur;
            else {
                $extension = pathinfo(TARGET . $image, PATHINFO_EXTENSION);
                $nomImageFinal = md5(uniqid()) . '.' . $extension;
                $cropped = $this->resizeThumbnailImage(TARGET . $image, TARGET . $nomImageFinal, $w, $h, $x1, $y1, $imageheight, $imagewidth);

                mysql_query("INSERT INTO event VALUES (default,'" . $subject . "','" . $content_happends . "','" . TARGET . $nomImageFinal . "',0," . $_SESSION['id_member'] . ",'" . date("Y-m-j H:i:s", ceil($date_debut / 1000)) . "','" . date("Y-m-j H:i:s", ceil($date_fin / 1000)) . "','" . $area . "','" . $country . "','" . $address . "'," . $event_confidentialite_info . ",'" . $lat . "','" . $lon . "'," . $time_option_debut . "," . $time_option_fin . "," . $nblimit . ")") or die(mysql_error());
                $id_event = mysql_insert_id();

                if ($event_confidentialite_info == 2) {
                    $values_insert_confidentialite = "";
                    $cp = 0;
                    foreach ($tab_membre_confidentialite as $t_tab_membre_confidentialite) {
                        $values_insert_confidentialite .= ($cp > 0 ? "," : "") . "(" . $id_event . "," . $t_tab_membre_confidentialite . ")";
                        $cp++;
                    }
                    mysql_query("INSERT INTO event_confidentiality VALUES " . $values_insert_confidentialite . "") or die(mysql_error());
                }

                $values_insert_passion = "";
                $cp = 0;
                foreach ($tab_passion as $t_tab_passion) {
                    $values_insert_passion .= ($cp > 0 ? "," : "") . "(" . $id_event . "," . $t_tab_passion . ")";
                    $cp++;
                }

                mysql_query("INSERT INTO event_passion VALUES " . $values_insert_passion . "") or die(mysql_error());
            }
        }
    }

    function modifyEvent() {
        global $reg;

        $erreur = "";

        $id_event = $_POST['id'];

        if (isset($id_event) && is_numeric($id_event)) {

            $requete = "SELECT * FROM event WHERE id_event='" . $id_event . "' AND id_member='" . $_SESSION['id_member'] . "'";
            $resultat = mysql_query($requete) or die(mysql_error());
            $data = mysql_fetch_array($resultat);
            $nb_data = mysql_num_rows($resultat);

            if ($nb_data == 1) {

                if (isset($_POST['image'])) {
                    if (isset($_POST['x1']) && isset($_POST['x2']) && isset($_POST['y1']) && isset($_POST['y2']) && isset($_POST['w']) && isset($_POST['h']) && isset($_POST['imageheight']) && isset($_POST['imagewidth'])) {
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
                }

                if (isset($_POST['subject']) && $_POST['subject'] != "" && $_POST['subject'] != "Saisissez le nom de l'évènement")
                    $subject = $reg->clean->POST('subject');
                else
                    $erreur .= "- Veuillez saisir le nom de l'évènement<br/>";

                if (isset($_POST['content_happends']) && $_POST['content_happends'] != "" && $_POST['content_happends'] != "Description...")
                    $content_happends = $reg->clean->POST('content_happends');
                else
                    $erreur .= "- Veuillez saisir une description<br/>";

                $country = $data['country_event'];
                $area = $data['area_event'];
                $address = $data['address_event'];
                $lat = $data['latitude_event'];
                $lon = $data['longitude_event'];
                if (isset($_POST['lon']) && isset($_POST['lat'])) {
                    if ($_POST['lon'] != $data['longitude_event'] || $_POST['lat'] != $data['latitude_event']) {
                        if ($_POST['lon'] != "" && $_POST['lat'] != "" || !is_numeric($_POST['lon']) || !is_numeric($_POST['lat'])) {
                            $r_google = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $_POST['lat'] . "," . $_POST['lon'] . "&sensor=false";
                            $return_google = file_get_contents($r_google);
                            $data_google = json_decode($return_google);

                            if ($data_google->status == 'OK') {
                                $country = "";
                                $area = "";
                                $address = "";
                                $lat = $_POST['lat'];
                                $lon = $_POST['lon'];
                                foreach ($data_google->results[0]->address_components as $val) {
                                    if ($val->types[0] == 'country')
                                        $country = $val->long_name;
                                    else if ($val->types[0] == 'administrative_area_level_1')
                                        $area = $val->long_name;
                                }
                                $address = $data_google->results[0]->formatted_address;

                                if (empty($country) || empty($area) || empty($address))
                                    $erreur .= "- Le lieu que vous avez indiqué manque de précision<br/>";
                            }
                            else if ($data_google['status'] == 'ZERO_RESULTS')
                                $erreur .= "- Il n'y a pas de résultats correspondant à votre lieu<br/>";
                            else
                                $erreur .= "- Un problème est survenu lors la récupération des données du lieu, réessayer ou contactez-nous si le problème persiste (" . $decode_google->status . ")<br/>";
                        }
                        else
                            $erreur .= "- Le lieu est incorrect<br/>";
                    }
                }
                else
                    $erreur .= "- Veuillez saisir la localisation de votre évènement<br/>";

                $nblimit = 0;
                if (isset($_POST['nblimit']) && !is_numeric($_POST['nblimit']))
                    $erreur .= "- Veuillez saisir un nombre pour le nombre de places";
                else if (isset($_POST['nblimit']))
                    $nblimit = $_POST['nblimit'];

                if (isset($_POST['event_confidentialite_info']) && ($_POST['event_confidentialite_info'] == 1 || $_POST['event_confidentialite_info'] == 2 || $_POST['event_confidentialite_info'] == 0))
                    $event_confidentialite_info = $_POST['event_confidentialite_info'];
                else
                    $erreur .= "- Veuillez vérifier les paramètres de confidentialité<br/>";

                if (isset($_POST['event_confidentialite_info']) && $_POST['event_confidentialite_info'] == 2 && $_POST['membre_confidentialite'] != "")
                    $membre_confidentialite = $_POST['membre_confidentialite'];
                else if (isset($_POST['event_confidentialite_info']) && $_POST['event_confidentialite_info'] == 2 && $_POST['membre_confidentialite'] == "")
                    $erreur .= "- Vous devez ajouter les contacts autorisés à voir votre évènement privé<br/>";

                if (isset($_POST['passion']) && $_POST['passion'] != "")
                    $passion = $_POST['passion'];
                else
                    $erreur .= "- Vous devez lier 2 mots clefs/passions minimum<br/>";

                if (isset($_POST['date_debut']) && isset($_POST['date_fin']) && is_numeric($_POST['date_fin']) && is_numeric($_POST['date_debut']) && $_POST['date_fin'] - $_POST['date_debut'] >= 0) {
                    $date_debut = $_POST['date_debut'];
                    $date_fin = $_POST['date_fin'];
                }
                else
                    $erreur .= "- Vous devez saisir une date début antérieure à la date de fin<br/>";

                if (isset($_POST['time_option2']) && $_POST['time_option2'] == 'checked')
                    $time_option_debut = 1;
                else
                    $time_option_debut = 0;

                if (isset($_POST['time_option1']) && $_POST['time_option1'] == 'checked')
                    $time_option_fin = 1;
                else
                    $time_option_fin = 0;


                if (!empty($erreur))
                    return json_encode(array('error', "<h2>Erreur</h2>" . $erreur));
                else {
                    if ($event_confidentialite_info == 2) {
                        $split_membre_confidentialite = explode(";", $membre_confidentialite);

                        $nb_confidentialite = 0;
                        $tab_membre_confidentialite = array();
                        foreach ($split_membre_confidentialite as $t_split_membre_confidentialite)
                            if (is_numeric($t_split_membre_confidentialite) && !in_array($t_split_membre_confidentialite, $tab_membre_confidentialite)) {
                                $nb_confidentialite++;
                                $tab_membre_confidentialite[] = $t_split_membre_confidentialite;
                            }

                        if (sizeof($tab_membre_confidentialite) > 0 && $nb_confidentialite > 0) {
                            $requete_verif_confidentialite = mysql_num_rows(mysql_query("SELECT id_member FROM member WHERE id_member IN (" . implode(",", $tab_membre_confidentialite) . ")"));

                            if ($requete_verif_confidentialite != $nb_confidentialite)
                                $erreur .= "- Vous devez autoriser des contacts valides à votre évènement<br/>";
                        }
                        else
                            $erreur .= "- Vous devez autoriser des contacts valides à votre évènement<br/>";
                    }

                    $split_passion = explode(";", $passion);
                    $nb_passion = 0;
                    $tab_passion = array();
                    foreach ($split_passion as $t_split_passion)
                        if (is_numeric($t_split_passion) && !in_array($t_split_passion, $tab_passion)) {
                            $nb_passion++;
                            $tab_passion[] = $t_split_passion;
                        }

                    if (sizeof($tab_passion) >= 1 && $nb_passion >= 1) {
                        $requete_verif_passion = mysql_num_rows(mysql_query("SELECT id_passion FROM passion WHERE id_passion IN (" . implode(",", $tab_passion) . ")"));

                        if ($requete_verif_passion != $nb_passion)
                            $erreur .= "- Vous devez ajouter 1 mot clef/passion valides<br/>";
                    }
                    else
                        $erreur .= "- Vous devez ajouter 1 mot clef/passion valide au minimum<br/>";

                    if (!empty($erreur))
                        return json_encode(array('error', "<h2>Erreur</h2>" . $erreur));

                    /*                     * *********************************************************************************************************************
                     * *************************************** CONFIDENTIALITE VERIF ****************************************************
                     */
                    $ancienneconfidentialite = $data['confidentiality_event'];
                    if (isset($_POST['isConfirmParticipant']) && isset($_POST['deleteParticipant'])) {

                        if ($_POST['deleteParticipant'] != '')
                            $delete_participant = explode(",", $_POST['deleteParticipant']);

                        //return json_encode(array("error-participant",$delete_participant));
                    }
                    else if ($ancienneconfidentialite != $_POST['event_confidentialite_info']) {

                        $result_check_confidentiality = array();

                        if (($ancienneconfidentialite == 2 || $ancienneconfidentialite == 1) && $_POST['event_confidentialite_info'] == 0) {
                            $requete_check_confidentiality = "SELECT * FROM event_participant inner join member on event_participant.id_member=member.id_member WHERE id_event='" . $id_event . "' AND event_participant.id_member NOT IN (SELECT id_contact FROM member_contacts WHERE id_member='" . $_SESSION['id_member'] . "' AND condition_contact=1) AND status='accepte'";
                            $resultat_check_confidentiality = mysql_query($requete_check_confidentiality) or die(mysql_error());
                            $resultat_check_confidentiality_nb = mysql_num_rows($resultat_check_confidentiality);
                            while ($data_check_confidentiality = mysql_fetch_array($resultat_check_confidentiality)) {
                                $result_check_confidentiality[] = $data_check_confidentiality;
                            }

                            if ($resultat_check_confidentiality_nb > 0)
                                return json_encode(array('error-confidentialite', $result_check_confidentiality));
                        }
                        else if (($ancienneconfidentialite == 1 || $ancienneconfidentialite == 0) && $_POST['event_confidentialite_info'] == 2 && isset($_POST['membre_confidentialite'])) {

                            $membre_confidentialite = $_POST['membre_confidentialite'];
                            $split_membre_confidentialite = explode(";", $membre_confidentialite);

                            $nb_confidentialite = 0;
                            $tab_membre_confidentialite = array();
                            foreach ($split_membre_confidentialite as $t_split_membre_confidentialite)
                                if (is_numeric($t_split_membre_confidentialite) && !in_array($t_split_membre_confidentialite, $tab_membre_confidentialite)) {
                                    $nb_confidentialite++;
                                    $tab_membre_confidentialite[] = $t_split_membre_confidentialite;
                                }

                            if (sizeof($tab_membre_confidentialite) > 0 && $nb_confidentialite > 0) {
                                $resultat_check_confidentiality = mysql_query("SELECT * FROM event_participant inner join member on event_participant.id_member=member.id_member WHERE status='accepte' AND id_event='" . $id_event . "' AND event_participant.id_member NOT IN (" . implode(",", $tab_membre_confidentialite) . ",'" . $_SESSION['id_member'] . "')");
                                $resultat_check_confidentiality_nb = mysql_num_rows($resultat_check_confidentiality);
                                while ($data_check_confidentiality = mysql_fetch_array($resultat_check_confidentiality)) {
                                    $result_check_confidentiality[] = $data_check_confidentiality;
                                }

                                if ($resultat_check_confidentiality_nb > 0)
                                    return json_encode(array('error-confidentialite', $result_check_confidentiality));
                            }
                        }
                    }
                }


                /*                 * ********************************************************************************
                 * ***************************** FIN CONFIDENTIALITE VERIF
                 */
                if (isset($_POST['image'])) {
                    $extension = pathinfo(TARGET . $image, PATHINFO_EXTENSION);
                    $nomImageFinal = md5(uniqid()) . '.' . $extension;
                    $cropped = $this->resizeThumbnailImage(TARGET . $image, TARGET . $nomImageFinal, $w, $h, $x1, $y1, $imageheight, $imagewidth);
                }

                // mysql_query("INSERT INTO event VALUES (default,'".$subject."','".$content_happends."','".TARGET.$nomImageFinal."',0,".$_SESSION['id_member'].",'".date("Y-m-j H:i:s",ceil($date_debut/1000))."','".date("Y-m-j H:i:s",ceil($date_fin/1000))."',".$cp.",'".$ville."','".$pays."','".$lieu."',".$event_confidentialite_info.",'".$lon."','".$lat."',".$time_option_debut.",".$time_option_fin.")") or die(mysql_error());

                $modif_request = array();
                if ($subject != $data['name_event'])
                    $modif_request[] = "name_event='" . $subject . "'";
                if ($content_happends != $data['description_event'])
                    $modif_request[] = "description_event='" . $content_happends . "'";
                if (isset($_POST['image']))
                    $modif_request[] = "image_event='" . TARGET . $nomImageFinal . "'";
                if (date("Y-m-j H:i:s", ceil($date_debut / 1000)) != $data['start_date_event'])
                    $modif_request[] = "start_date_event='" . date("Y-m-j H:i:s", ceil($date_debut / 1000)) . "'";
                if (date("Y-m-j H:i:s", ceil($date_fin / 1000)) != $data['end_date_event'])
                    $modif_request[] = "end_date_event='" . date("Y-m-j H:i:s", ceil($date_fin / 1000)) . "'";
                if ($lon != $data['longitude_event'] || $lat != $data['latitude_event']) {
                    $modif_request[] = "longitude_event='" . $lon . "'";
                    $modif_request[] = "latitude_event='" . $lat . "'";
                    if ($area != $data['area_event'])
                        $modif_request[] = "area_event='" . $area . "'";
                    if ($country != $data['country_event'])
                        $modif_request[] = "country_event='" . $country . "'";
                    if ($address != $data['address_event'])
                        $modif_request[] = "address_event='" . $address . "'";
                }
                if ($ancienneconfidentialite != $event_confidentialite_info)
                    $modif_request[] = "confidentiality_event='" . $event_confidentialite_info . "'";
                if ($time_option_debut != $data['time_option_start_event'])
                    $modif_request[] = "time_option_start_event='" . $time_option_debut . "'";
                if ($time_option_fin != $data['time_option_end_event'])
                    $modif_request[] = "time_option_end_event='" . $time_option_fin . "'";
                if ($nblimit != $data['nblimit'])
                    $modif_request[] = "nblimit_event=" . $nblimit;

                if (count($modif_request) > 0)
                    mysql_query("UPDATE event SET " . implode(",", $modif_request) . " WHERE id_event='" . $id_event . "'") or (die(mysql_error()));

                if (isset($_POST['image']) && file_exists($data['image_event']))
                    unlink($data['image_event']);
                if (isset($_POST['isConfirmParticipant']) && isset($delete_participant)) {

                    if (count($delete_participant) > 0)
                        mysql_query("DELETE FROM event_participant WHERE status='accepte' AND id_event='" . $id_event . "' AND id_member IN (" . implode(",", $delete_participant) . ")") or die(mysql_error());
                }

                if ($event_confidentialite_info == 2) {
                    $values_insert_confidentialite = "";
                    $cp = 0;
                    foreach ($tab_membre_confidentialite as $t_tab_membre_confidentialite) {
                        $values_insert_confidentialite .= ($cp > 0 ? "," : "") . "(" . $id_event . "," . $t_tab_membre_confidentialite . ")";
                        $cp++;
                    }
                    mysql_query("DELETE FROM event_confidentiality WHERE id_event='" . $id_event . "'") or (die(mysql_error()));
                    mysql_query("INSERT INTO event_confidentiality VALUES " . $values_insert_confidentialite . "") or die(mysql_error());
                }

                $values_insert_passion = "";
                $cp = 0;
                foreach ($tab_passion as $t_tab_passion) {
                    $values_insert_passion .= ($cp > 0 ? "," : "") . "(" . $id_event . "," . $t_tab_passion . ")";
                    $cp++;
                }

                mysql_query("DELETE FROM event_passion WHERE id_event='" . $id_event . "'");
                mysql_query("INSERT INTO event_passion VALUES " . $values_insert_passion . "") or die(mysql_error());

                if ($ancienneconfidentialite == 2 && $event_confidentialite_info != $ancienneconfidentialite)
                    mysql_query("DELETE FROM event_confidentiality WHERE id_event='" . $id_event . "'") or (die(mysql_error()));
                return json_encode(array("ok"));
            }
        }
    }

    //private

    function resizeThumbnailImage($image, $thumb_image_name, $w, $h, $start_width, $start_height, $imageheight, $imagewidth) {

        $tAttribut = getimagesize($image);
        $extension = pathinfo(TARGET . $image, PATHINFO_EXTENSION);

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

        $scale_w = 294 / $w;
        $scale_h = 294 / $h;

        $newImageWidth = ceil($w * $scale_w);
        $newImageHeight = ceil($h * $scale_h);
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

    function addComment() {

        global $reg;

        $message = $reg->clean->POST('message');
        $idevent = $_POST['idevent'];
        $datenow = time();

        $result = array();

        if (!empty($message) && strlen($message) > 2 && isset($_SESSION['id_member']) && !empty($idevent) && is_numeric($idevent)) {

            $requete_membre = mysql_query("SELECT pseudo_member,photo_member FROM member WHERE id_member='" . $_SESSION['id_member'] . "'") or die(mysql_error());
            $data_membre = mysql_fetch_array($requete_membre);

            if (mysql_num_rows($requete_membre) != 0) {

                $requete_evenement = mysql_query("SELECT * FROM event WHERE id_event='" . $idevent . "'") or die(mysql_error());
                $data_evenement = mysql_fetch_array($requete_evenement);
                $nb_evenement = mysql_num_rows($requete_evenement);

                if ($nb_evenement == 1) {

                    $previous_time = 0;
                    $last_message = mysql_query("SELECT * FROM event_comments WHERE id_event='" . $idevent . "' AND id_member='" . $_SESSION['id_member'] . "' ORDER BY date_comment DESC LIMIT 1") or die(mysql_error());
                    if (mysql_num_rows($last_message) != 0) {
                        $data_previous_message = mysql_fetch_array($last_message);
                        $previous_time = strtotime($data_previous_message['date_comment']);
                    }

                    if ($datenow - $previous_time > 35 || $previous_time == 0) {
                        mysql_query("INSERT INTO event_comments VALUES ('" . $_SESSION['id_member'] . "','" . $idevent . "','" . $message . "',default)") or die(mysql_error());
                        $result[] = "ok";
                        $result[] = array(ucfirst($data_membre['pseudo_member']), $data_membre['photo_member'], $message, date("d-m-Y à H:i", $datenow), -1);
                    } else {
                        $result[] = "error";
                        $result[] = "Evitez les spams";
                    }
                } else {
                    $result[] = "error";
                    $result[] = "Mauvais évènement";
                }
            } else {
                $result[] = "error";
            }
        } else {
            $result[] = "error";
            $result[] = "Veuillez saisir un message correct (plus de 2 caractères)";
        }

        return json_encode($result);
    }

    function fan() {

        $idevent = $_GET['idevent'];

        if (!empty($idevent) && is_numeric($idevent)) {
            $requete_event = mysql_query("SELECT * FROM event WHERE id_event='" . $idevent . "'") or die(mysql_error());
            $data_event = mysql_fetch_array($requete_event);

            $verif_confidentialite = true;

            if ($data_event['id_member'] == $_SESSION['id_member']) {
                
            } else if ($data_event['confidentiality_event'] == 0) {
                $requete_friend = mysql_query("SELECT * FROM member_contacts WHERE id_member='" . $_SESSION['id_member'] . "' AND id_contact='" . $data_event['id_member'] . "' AND condition_contact=1 OR id_contact='" . $_SESSION['id_member'] . "' AND id_member='" . $data_event['id_member'] . "' AND condition_contact=1");
                $nb_friend = mysql_num_rows($requete_friend);

                $nb_confidentialite = 0;
                if ($nb_friend != 2) {
                    $requete_confidentialite = mysql_query("SELECT * FROM event_participant WHERE id_member='" . $_SESSION['id_member'] . "' AND id_event='" . $idevent . "' AND status='accepte'") or die(mysql_error());
                    $nb_confidentialite = mysql_num_rows($requete_confidentialite);
                }

                if ($nb_friend != 2 || $nb_confidentialite == 1)
                    $verif_confidentialite = false;
            }
            else if ($data_event['confidentiality_event'] == 2) {
                $requete_confidentialite = mysql_query("SELECT * FROM event_confidentiality WHERE id_member='" . $_SESSION['id_member'] . "' AND id_event='" . $idevent . "'") or die(mysql_error());
                $nb_confidentialite = mysql_num_rows($requete_confidentialite);

                if ($nb_confidentialite != 1) {
                    $requete_confidentialite = mysql_query("SELECT * FROM event_participant WHERE id_member='" . $_SESSION['id_member'] . "' AND id_event='" . $idevent . "' AND status='accepte'") or die(mysql_error());
                    $nb_confidentialite = mysql_num_rows($requete_confidentialite);
                }

                if ($nb_confidentialite != 1)
                    $verif_confidentialite = false;
            }

            if ($verif_confidentialite) {
                $requete_verif_participate = mysql_query("SELECT * FROM event_fan WHERE id_event='" . $idevent . "' AND id_member='" . $_SESSION['id_member'] . "'") or die(mysql_error());
                $nb_verif_participate = mysql_num_rows($requete_verif_participate);

                if ($nb_verif_participate == 1) {
                    mysql_query("DELETE FROM event_fan WHERE id_event='" . $idevent . "' AND id_member='" . $_SESSION['id_member'] . "'") or die(mysql_error());
                    return "Je suis fan";
                } else {
                    mysql_query("INSERT INTO event_fan VALUES ('" . $_SESSION['id_member'] . "','" . $idevent . "',default)") or die(mysql_error());
                    return "Je ne suis plus fan";
                }
            }
        }
    }

    function participate() {

        $idevent = $_GET['idevent'];

        if (!empty($idevent) && is_numeric($idevent)) {
            $requete_event = mysql_query("SELECT * FROM event WHERE id_event='" . $idevent . "'") or die(mysql_error());
            $data_event = mysql_fetch_array($requete_event);

            $verif_confidentialite = true;
            if ($data_event['id_member'] == $_SESSION['id_member']) {
                
            } else if ($data_event['confidentiality_event'] == 2) {
                $requete_confidentialite = mysql_query("SELECT * FROM event_confidentiality WHERE id_member='" . $_SESSION['id_member'] . "' AND id_event='" . $idevent . "'") or die(mysql_error());
                $nb_confidentialite = mysql_num_rows($requete_confidentialite);

                if ($nb_confidentialite != 1)
                    $verif_confidentialite = false;
            }
            else if ($data_event['confidentiality_event'] == 0) {
                $requete_friend = mysql_query("SELECT * FROM member_contacts WHERE id_member='" . $_SESSION['id_member'] . "' AND id_contact='" . $data_event['id_member'] . "' AND condition_contact=1 OR id_contact='" . $_SESSION['id_member'] . "' AND id_member='" . $data_event['id_member'] . "' AND condition_contact=1");
                $nb_friend = mysql_num_rows($requete_friend);

                if ($nb_friend != 2)
                    $verif_confidentialite = false;
            }

            if ($verif_confidentialite && strtotime($data_event['end_date_event']) > time()) {
                $requete_verif_participate = mysql_query("SELECT * FROM event_participant WHERE id_event='" . $idevent . "' AND id_member='" . $_SESSION['id_member'] . "'") or die(mysql_error());
                $nb_verif_participate = mysql_num_rows($requete_verif_participate);

                if ($nb_verif_participate == 1) {
                    mysql_query("DELETE FROM event_participant WHERE id_event='" . $idevent . "' AND id_member='" . $_SESSION['id_member'] . "'") or die(mysql_error());
                    return "ok;Je viendrai";
                } else {
                    mysql_query("INSERT INTO event_participant VALUES ('" . $_SESSION['id_member'] . "','" . $idevent . "',default,'accepte',default)") or die(mysql_error());
                    return "ok;Je ne viens plus";
                }
            } else if ($data_event['confidentiality_event'] == 0 || $data_event['confidentiality_event'] == 2 && !$verif_confidentialite && strtotime($data_event['end_date_event']) > time()) {
                /* $requete_verif_participate = mysql_query("SELECT id_event FROM event_participant WHERE id_event='".$idevent."' AND id_member='".$_SESSION['id_member']."' AND status IN ('','')") or die(mysql_error());
                  $data_verif_participate = mysql_fetch_array($requete_verif_participate);
                  $nb_verif_participate = mysql_num_rows($requete_verif_participate);

                  if($nb_verif_participate == 0) {
                  mysql_query("INSERT INTO event_participant VALUES ('".$_SESSION['id_member']."','".$idevent."',default,'demande')") or die(mysql_error());
                  echo "ok;J'annule ma demande";
                  }
                  else if($data_verif_participate['status'] == 1 || $data_verif_participate['status'] == 0) {
                  mysql_query("DELETE FROM event_participant WHERE id_member='".$_SESSION['id_member']."' AND id_event='".$idevent."'") or die(mysql_error());
                  echo "ok;Je voudrais venir";
                  } */
            }
            // TRAITER CAS:  - JE SUIS INVITE PAR PARTICIPANT ET J'ACCEPTE DE VENIR ET EVENEMENT NON TERMINE ET JE SUIS ETRANGER AU CONFIDENTIALITE
            // - JE SUIS INVITE PAR AUTEUR ET J'ACCEPTE DE VENIR ET EVENEMENT NON TERMINE ET JE SUIS ETRANGER AU CONFIDENTIALITE
            // EVENEMENT TERMINE ET J'Y ETAIS
        }
    }

}

?>
