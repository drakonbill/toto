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

class libs_user {

    // Informations of the user 
    private $userData;

    // All informations of the member are stocked in the global variable $userData
    function __construct($id) {
        global $reg;
        // If there is no ID in parametre 
        if (!isset($id))
            $id = "-1";

        // Select basic member informations + member_details
        $query = mysql_query("SELECT * FROM member M, member_details D WHERE M.id_member = D.id_member AND M.id_member = '$id'", $reg->dbcon) or die(mysql_error());
        while ($row = mysql_fetch_assoc($query)) {
            foreach ($row as $key => $value) {
                $this->userData[$key] = $value;
            }
        }
    }

    // Get the information you need in the $name variable
    public function __get($name) {
        if (isset($this->userData[$name]))
            return $this->userData[$name];
        else
            return 0;
    }

    // Change an information of the member 
    function __set($name, $value) {
        $this->userData[$name] = $value;
    }

    // Check if the information is stocked or not
    function __isset($name) {
        return isset($this->userData[$name]);
    }

    // Check is an id is stocked on a session or not : if the member is logged or not
    function loggedin() {
        return isset($_SESSION['id_member']);
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
        $query = mysql_query("SELECT $select FROM member WHERE id_member = '$id'", $reg->dbcon) or die(mysql_error());


        return mysql_fetch_array($query);
    }

    // Delete a selected informations 
    function __delete($name) {
        if (isset($this->userData[$name])) {
            unset($this->userData[$name]);
        }
    }

    // Update an information
    function __update($name, $value) {
        if ($this->userData[$name] != $value) {
            $this->userData[$name] = $value;
        }
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

}

?>
