<?php

/* Class : libs_user
 * Function : have all informations on each page of the user actually logged
 * Version : 0.1
 * Date : 16/04/13
 * @Author : Miki and Quentin
 */

// For the fonction of crypting (password)
define("PREFIXE", "prefixeprotecmeeto");
define("SUFFIXE", "suffixeprotecparty");

class libs_user extends user {
    function __construct($id) {
          parent::__construct($id);
          $this->insertTable("member_details", "id_member", $id);
      }

    function photoURL($id = '') {
        global $reg;


        if ($id == '' || $id == $this->id_member)
            $photo_member = $this->photo_member == "" ? $reg->appconf["memberdir"] . "no-photo.jpg" : $this->photo_member;
        else {
            $rez = $this->getDB("photo_member", $id);
            $photo_member = $rez[0] == "" ? $reg->appconf["memberdir"] . "m-no-photo.jpg" : $rez[0];
        }


        return "/" . $photo_member;
    }

    function getDB($select, $id) {

        global $reg;
        $query = mysql_query("SELECT $select FROM member NATURAL JOIN member_details WHERE id_member = '$id'", $reg->dbcon) or die(mysql_error());


        return mysql_fetch_array($query);
    }



// To crypt the password of a member
    function hacher($passe) {
        $passe = md5(sha1(PREFIXE) . $passe . sha1(SUFFIXE));
        return $passe;
    }

// Function to delete empty cookies, because of a bugue with the logout
    function clean_cookie() {

        if (isset($_COOKIE['logout'])) {
            if ($_COOKIE['logout'] == 1) {
                setcookie('logout', null, time() - 3600, '/');
                header("Refresh : 0; url=");
            }
        } else {
            return 0;
        }
    }

// Function to send and email proprely
    function postMail($messtxt, $messhtml, $sujet, $to, $from, $reply = "") {
        if (empty($reply))
            $reply = $from;
        $destinataire = "$to[0] <$to[1]>";
        $boundary = "_" . md5(uniqid(rand()));
        $entete = "MIME-Version: 1.0\n";

        $entete .= "X-Sender: <" . $_SERVER['SERVER_NAME'] . ">\n";
        $entete .= "X-Mailer: PHP\n";
        $entete .= "X-auth-smtp-user: webmaster@" . $_SERVER['SERVER_NAME'] . " \n";
        $entete .= "X-abuse-contact: webmaster@" . $_SERVER['SERVER_NAME'] . " \n";

        $entete .= "Reply-to: $reply[0] <$reply[1]>\n";
        $entete .= "From:$from[0] <$from[1]>\n";
        $entete .= "Content-Type: multipart/alternative; boundary=\"$boundary\"";

        $message = "--" . $boundary . "\n";
        $message.= "This is a multi-part message in MIME format.\n\n";

        $message .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
        $message .= "Content-Transfer-Encoding: quoted-printable\n\n";
        $message .= $messtxt;
        $message .= "\n\n";
        $message .= "--" . $boundary . "\n";
        $message .= "Content-Type: text/html; charset=\"UTF-8\"\n";
        $message .= "Content-Transfer-Encoding: quoted-printable\n\n";
        $message .= str_replace("=", "=3D", $messhtml);
        $message .= "\n\n";
        debug($destinataire . $sujet . $message . $entete);
        return mail($destinataire, $sujet, $message, $entete);
    }

    function sendValidationMail($email) {


// Email sending
        $c = rand(10000000, 99999999);
        $code = md5($c);
        $l = "meetoparty/account/confirmationregistration/code/$code/pseudo/$pseudo";
        $lien = "<a href='$l'>Validation de votre inscription</a>";
        mysql_query("UPDATE member SET code_member = '$code' WHERE email_member = '$email'") or die(mysql_error());

// To, from et reply en array
        $to = array('', $email);
        $sujet = "Inscription sur Meetoparty";
        $messtxt = "<p>Bonjour, <br> Vous êtes actuellement en train de vous inscrire sur Meetoparty. <br> Nous vous remercions des intérêts que vous portez à nos services.<br> Afin que votre inscription soit complète, merci de cliquer sur le lien ci-dessous pour la valider : <br></p>";
        $messtxt .= "$lien<br><br>";
        $messtxt .= "Merci,<br> A bientôt sur notre site <br> L'équipe de Meetoparty";
        $messhtml = '<p>' . $messtxt . '</p>';
        $from = array('Meetoparty', "no-reply@meetoparty.fr");

        $this->postMail($messtxt, $messhtml, $sujet, $to, $from, $reply = "");
    }

//Function to get the IP of the member
    function get_ip() {
        return (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    }

    public static function getID($pseudo) {
        $requete = mysql_query("SELECT * FROM member WHERE pseudo_member='$pseudo'");
        if (mysql_num_rows($requete) == 1) {
            $id = mysql_fetch_array($requete);
            return $id['id_member'];
        }
        else
            return false;
    }

    public function Age($naiss) {

        list($annee, $mois, $jour) = split('[-.]', $naiss);
        $today['mois'] = date('n');
        $today['jour'] = date('j');
        $today['annee'] = date('Y');
        $annees = $today['annee'] - $annee;
        if ($today['mois'] <= $mois) {
            if ($mois == $today['mois']) {
                if ($jour > $today['jour'])
                    $annees--;
            }
            else
                $annees--;
        }
        return $annees;
    }

    /*
     * Funcion to have the day with a date formated in the database YYYY-MM-DD
     * @return : 03 for example if you are born the third of a month
     */

    public function Daydate($naiss) {

        list($annee, $mois, $jour) = split('[-.]', $naiss);

        return $jour;
    }

    /*
     * Funcion to have the month with a date formated in the database YYYY-MM-DD
     * @return : July for example if you are born this month
     */

    public function Monthdate($naiss) {

        list($annee, $mois, $jour) = split('[-.]', $naiss);

        return $mois;
    }

    /*
     * Funcion to have the year with a date formated in the database YYYY-MM-DD
     * @return : 2015 for example 
     */

    public function Yeardate($naiss) {

        list($annee, $mois, $jour) = split('[-.]', $naiss);

        return $annee;
    }

    public function month($m) {
        if ($m == "01") {
            return $result = "Janvier";
        }
        if ($m == "02") {
            return $result = "Février";
        }
        if ($m == "03") {
            return $result = "Mars";
        }
        if ($m == "04") {
            return $result = "Avril";
        }
        if ($m == "05") {
            return $result = "Mai";
        }
        if ($m == "06") {
            return $result = "Juin";
        }
        if ($m == "07") {
            return $result = "Juillet";
        }
        if ($m == "08") {
            return $result = "Août";
        }
        if ($m == "09") {
            return $result = "Septembre";
        }
        if ($m == "10") {
            return $result = "Octobre";
        }
        if ($m == "11") {
            return $result = "Novembre";
        }
        if ($m == "12") {
            return $result = "Décembre";
        }
    }

    /*
     * Funcion to have the region with the postal code in param
     * @return : The name of the region : Example : Postal code 37300, Region : Centre.
     */

    public function CodeRegionName($postalcode) {

        $cp = substr($postalcode, 0, 2);

        $requete = mysql_query("SELECT R.nom FROM regions R, departements D WHERE R.region_id = D.region_id AND D.numero='$cp'");

        if (mysql_num_rows($requete) == 1) {
            $region = mysql_fetch_array($requete);
            return $region['nom'];
        } else {
            $region = "Région non trouvée";
            return $region;
        }
    }

    /*
     * Funcion to have the region with the postal code in param
     * @return : The id of the region : Example : Postal code 37300, Region : 3
     */

    public function CodeRegionId($postalcode) {

        $cp = substr($postalcode, 0, 2);

        $requete = mysql_query("SELECT R.region_id FROM regions R, departements D WHERE R.region_id = D.region_id AND D.numero='$cp'");

        if (mysql_num_rows($requete) == 1) {
            $region = mysql_fetch_array($requete);
            return $region['region_id'];
        } else {
            $region = "Région non trouvée";
            return $region;
        }
    }

    // New function to know if the profil is to the member or not
    // @return : true if it's own profile, false if not.
    function own($id) {
       if ($id == $this->id_member) return true;
        else
            return false;
    }

}

?>
