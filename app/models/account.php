<?php

class models_account extends Model {

// No need for the moment
    public function indexModel() {
        
    }

// Login Model
    public function login() {

// Variable definition : Email, Password, and Token
        $loginEmail = $this->reg->clean->POST('email');
// I want to include the hacher function in the user lib ... But don't know how to do that 
        $loginPassword = $this->reg->clean->POST('password');
        $password = $this->reg->user->hacher($loginPassword); //md5(sha1(PREFIXE) . $loginPassword  . sha1(SUFFIXE));
        $loginToken = $this->reg->clean->POST('token');

// For the coockie : var memoriser, if it's check : cookies on, if not, no cookies.

        $logincookie = $this->reg->clean->POST('memoriser');

// If the token is not empty
        if (isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($loginToken)) {
// If the token in session, and sent with the connexion form are equal
            if ($_SESSION['token'] == $loginToken) {
// During of the token
                $timestamp_old = time() - (10 * 60);
// If the token has expiered 
                if ($_SESSION['token_time'] >= $timestamp_old) {
// If the cookie is not empty
                    if (!isset($_COOKIE['email_member']) OR !isset($_COOKIE['password_member'])) {

// Email selection 
                        $query = mysql_query("SELECT * from member WHERE email_member = '$loginEmail'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query) != 1) {
                            $data["error"]["email"] = "L'adresse email que vous avez entré est inconnue.";
                        }

// Password selection with the good email, to see if the email OR the password is false. To know which one is false.
                        $query2 = mysql_query("SELECT id_member from member WHERE email_member = '$loginEmail' AND password_member = '$password'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query2) == 1) {

                            $row = mysql_fetch_assoc($query2);

                            $data = $row;

                            if ($_SESSION['account_locked'] < 10) {
// ID of the member in session 
                                $_SESSION['id_member'] = $data['id_member'];
                            } else {
                                $data["error"]["locked"] = "A cause d'un grand nombre de tentatives, votre session est temporairement bloquée.";
                            }
// cookies 
                            if ($logincookie == 1) {
                                $expire = 7 * 24 * 3600; //durée du cookie à 7 jours
                                $tempo_email = $loginEmail;
                                $tempo_password = $password;
                                setcookie('email_member', $tempo_email, time() + $expire);
                                setcookie('password_member', $tempo_password, time() + $expire);
                                setcookie('logout', 1, time() + $expire);
                            }
                        } else {
                            $data["error"]["password"] = "Le mot de passe que vous avez entré n'est pas correct.";
                            // Installation of locked account with sessions if the member aptent 10 times
                            if (!isset($_SESSION['account_locked'])) {
                                $_SESSION['account_locked'] = 1;
                            }

                            if ((isset($_SESSION['account_locked'])) && ($_SESSION['account_locked'] < 10)) {
                                $_SESSION['account_locked'] = $_SESSION['account_locked'] + 1;
                            }
                            if ((isset($_SESSION['account_locked'])) && ($_SESSION['account_locked'] == 10)) {
                                $data["error"]["locked"] = "A cause d'un grand nombre de tentatives, votre session est temporairement bloquée.";
                            }
                        }
                    } else {
                        $cookieEmail = $_COOKIE['email_member'];
                        $cookiePassword = $_COOKIE['password_member'];

                        $query = mysql_query("SELECT id_member from member WHERE email_member = '$cookieEmail' AND password_member = '$cookiePassword'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query) == 1) {

                            $row = mysql_fetch_assoc($query);

                            $data = $row;
// ID of the member in session 
                            $_SESSION['id_member'] = $data['id_member'];
                        } else {
                            $data["error"]["cookie"] = "Une erreur est survenue lors de la mémorisation de votre connexion. Veuillez réessayer.";
                            setcookie('email_member', "", time() - 1000);
                            setcookie('password_member', "", time() - 1000);

                            if (!isset($_SESSION['account_locked'])) {
                                $_SESSION['account_locked'] = 1;
                            }

                            if ((isset($_SESSION['account_locked'])) && ($_SESSION['account_locked'] < 10)) {
                                $_SESSION['account_locked'] = $_SESSION['account_locked'] + 1;
                            }
                            if ((isset($_SESSION['account_locked'])) && ($_SESSION['account_locked'] == 10)) {
                                $data["error"]["locked"] = "A cause d'un grand nombre de tentatives, votre session est temporairement bloquée.";
                            }
                        }
                    }
                } else {
// Error in the global variable data
                    $data['error']['token'] = "La connexion a expirée. Veuillez vous identifier de nouveau.";
                }
            } else {
                $data['error']['token'] = "Un problème est arrivé durant la connexion. Veuillez réessayer.";
                header('Location: /index');
            }
        } else {
            $data['error']['token'] = "Jeton d'accès inexistant.";
            header('Location: /index');
        }
        return $data;
    }

    // Logout Model

    public function logout() {

        // Unset id of the member in session
        unset($_SESSION['id_member']);

        // Destroying the cookie
        if ((!empty($_COOKIE['email_member'])) && (!empty($_COOKIE['password_member']))) {
            setcookie('email_member', null, time() - 3600, '/');
            setcookie('password_member', null, time() - 3600, '/');
            // unset($_COOKIE['email_member']);
            // unset($_COOKIE['password_member']);
        }
    }

    public function lostpassword() {
        
    }

    public function validationregistration() {

        // Installing Token system for the validation of the registration 
        $loginToken = $this->reg->clean->POST('token');

// If the token is not empty
        if (isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($loginToken)) {
// If the token in session, and sent with the connexion form are equal
            if ($_SESSION['token'] == $loginToken) {
// During of the token
                $timestamp_old = time() - (10 * 60);
// If the token has expiered 
                if ($_SESSION['token_time'] >= $timestamp_old) {

                    // All informations in the registration form 
                    $pseudo = $this->reg->clean->POST('pseudo');
                    $loginPassword = $this->reg->clean->POST('password');
                    $password = $this->reg->user->hacher($loginPassword);
                    $loginConfirmation = $this->reg->clean->POST('register-confirmation-password');
                    $confirmation = $this->reg->user->hacher($loginConfirmation);
                    $email = $this->reg->clean->POST('register-email');
                    $day = $this->reg->clean->POST('register-jour');
                    $month = $this->reg->clean->POST('register-mois');
                    $year = $this->reg->clean->POST('register-annee');
                    $date = "$year-$day-$month";
                    $homme = $this->reg->clean->POST('register-etes-1');
                    $femme = $this->reg->clean->POST('register-etes-2');
                    $pays = $this->reg->clean->POST('pays');
                    $cp = $this->reg->clean->POST('register-code-postal');
                    $ville = $this->reg->clean->POST('register-ville');
                    $conditions = $this->reg->clean->POST('accepte');
                    $ip = $this->reg->user->get_ip();

                    if (empty($pseudo))
                        $data["error"]["pseudo"] = "<p>Veuillez remplir le pseudo.</p><br>";

                    if (empty($email))
                        $data["error"]['email'] = "<p>Veuillez remplir le mail.</p><br>";

                    if (empty($password))
                        $data["error"]['password'] = "<p>Veuillez remplir le mot de passe.</p><br>";

                    if (empty($confirmation))
                        $data["error"]['confirmation'] = "<p>Veuillez remplir le mot de passe de vérification.</p><br>";

                    if ((empty($day)) or (empty($month) or (empty($year))))
                        $data["error"]['birth'] = "<p>Veuillez remplir la date de naissance.</p><br>";

                    if (empty($pays))
                        $data["error"]['pays'] = "<p>Veuillez choisir un pays.</p><br>";

                    if (empty($cp))
                        $data["error"]['cp'] = "<p>Veuillez remplir le code postal.</p><br>";

                    if (empty($conditions))
                        $data["error"]['conditions'] = "<p>Veuillez accepter les conditions d'utilisation.</p><br>";

                    if ((strlen($pseudo) < 2) or (strlen($pseudo) > 40))
                        $data["error"]['pseudo-length'] = "<p>Le pseudo doit être compris entre 2 et 40 caractères.</p><br>";

                    if ((strlen($password) < 2) or (strlen($password) > 40))
                        $data["error"]['password-length'] = "<p>Le mot de passe doit être compris entre 2 et 40 caractères.</p><br>";

                    if ((strlen($confirmation) < 2) or (strlen($confirmation) > 40))
                        $data["error"]['confirmation-length'] = "<p>Le mot de passe de vérification doit être compris entre 2 et 40 caractères.</p><br>";

                    if ($confirmation != $password)
                        $data["error"]['pass-conf'] = "<p>Les mot de passe doivent être identiques.</p><br>";

                    if ((strlen($email) < 5) or (strlen($email) > 70))
                        $data["error"]['mail-length'] = "<p>La taille de l'email est incorrecte.</p><br>";

                    if (strlen($day) > 2)
                        $data["error"]['birth-day'] = "<p>Le jour de naissance doit être de la forme JJ.</p><br>";

                    if (strlen($month) > 2)
                        $data["error"]['birth-month'] = "<p>Le mois de naissance doit être de la forme MM.</p><br>";

                    if (strlen($year) > 4)
                        $data["error"]['birth-year'] = "<p>L'année de naissance doit être de la forme AAAA.</p><br>";

                    if ((strlen($cp) < 3) or (strlen($cp) > 5))
                        $data["error"]['cp-length'] = "<p>La taille du code postal est incorrecte.</p><br>";

                    if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email))
                        $data["error"]['mail-format'] = "<p>L'email n'est pas au format valide.</p><br>";

                    if (($day < 1) or ($day > 31))
                        $data["error"]['day-length'] = "<p>Le jour de naissance doit être un nombre.</p><br>";

                    if (($month < 1) or ($month > 12))
                        $data["error"]['month-length'] = "<p>Le mois de naissance doit être un nombre.</p><br>";

                    if (($year < 1880) or ($year > 2300))
                        $data["error"]['year-length'] = "<p>L'année de naissance doit être un nombre.</p><br>";

                    if (($cp < 1) or ($cp > 99999))
                        $data["error"]['cp-format'] = "<p>Le code postal doit être un nombre.</p><br>";

                    if (!empty($day) && (!empty($month)) && (!empty($year)))
                        $date_de_naissance = "$day/$month/$year";

                    $chiffre = explode('/', $date_de_naissance);
                    $time_naissance = mktime(0, 0, 0, $chiffre[1], $chiffre[0], $chiffre[2]);
                    $seconde_vecu = time() - $time_naissance;
                    $seconde_par_an = (1461 * 24 * 60 * 60) / 4;
                    $age = floor(($seconde_vecu / $seconde_par_an) + 1);

                    if ($age < 16)
                        $data["error"]['age'] = "<p>Il faut être majeur pour accéder au site.</p><br>";

                    // SEX : 1 = Men, 2 = Women
                    if (!empty($homme))
                        $sexe = 1;

                    if (!empty($femme))
                        $sexe = 2;

                    $query = mysql_query("SELECT pseudo_member from member WHERE pseudo_member = '$pseudo'") or die("Impossible de sélectionner le pseudo : " . mysql_error());

                    if (mysql_num_rows($query) == 1) {
                        $row = mysql_fetch_assoc($query);
                        $result1 = $row;

                        if (!empty($result1['pseudo_member']))
                            $data["error"]['pseudo-verif'] = "<p>Votre pseudo est déjà utilisé, veuillez en choisir un autre.</p><br>";
                    }

                    $query2 = mysql_query("SELECT email_member from member WHERE email_member = '$email'") or die("Impossible de sélectionner l'email : " . mysql_error());

                    if (mysql_num_rows($query2) == 1) {
                        $row = mysql_fetch_assoc($query2);
                        $result2 = $row;

                        if (!empty($result2['email_member']))
                            $data["error"]['mail-verif'] = "<p>Votre email est déjà enregistré, veuillez en choisir un autre.</p><br>";
                    }

                    if (empty($data['error'])) {
                        mysql_query("INSERT INTO member (pseudo_member, password_member, email_member, birth_member, inscription_date_member, ip_member, zipcode_member, city_member, sex_member, country_member) VALUES ('$pseudo', '$password', '$email', '$date', NOW(), '$ip', '$cp', '$ville', '$sexe','$pays')");
                        $lastid = mysql_insert_id();
                        mysql_query("INSERT INTO member_details (id_member) VALUES ('$lastid')");

                        $this->reg->user->sendValidationMail($email); //this will be needed again, if user reguest for new validation

                        mkdir("memberdir/" . hash('crc32', crc32(PREFIXE) . $lastid . crc32(SUFFIXE)), 0777);

                        $data["result"] = "<p>Vous allez bientôt recevoir un mail de validation pour activer vore compte.</p><br>";
                        $data["result"] .= "<p>Vous allez être redirigé automatiquement dans 5 secondes vers l'accueil du site.</p>";
                        header('Refresh: 7; url=/index');
                    } else {
                        $data["error"]['result'] = "<p>Erreur lors de l'inscription</p><br>";
                    }
                } else {
// Error in the global variable data
                    $data['error']['token'] = "La connexion a expirée. Veuillez vous identifier de nouveau.";
                }
            } else {
                $data['error']['token'] = "Un problème est arrivé durant la connexion. Veuillez réessayer.";
                header('Location: /index');
            }
        } else {
            $data['error']['token'] = "Jeton d'accès inexistant.";
            header('Location: /index');
        }
        return $data;
    }

// Validation of registration in email 
    public function confirmationregistration() {

        //   $l = "meetoparty/account/confirmationregistration/code/$code/pseudo/$pseudo";
        global $_URL;

        $c = mysql_real_escape_string($_URL['code']);
        $p = mysql_real_escape_string($_URL['pseudo']);

        $pseudo = $p;
        $code = $c;

        if (empty($pseudo) or empty($code))
            $data['error']['empty'] = "Il y a eu un problème lors de votre inscription.<br> Veuillez contacter les webmaster du site";

        if (empty($data['error']['empty'])) {

            $query = mysql_query("SELECT pseudo_member from member WHERE pseudo_member = '$pseudo'") or die("Impossible de sélectionner l'email : " . mysql_error());

            if (mysql_num_rows($query) == 1) {
                $row = mysql_fetch_assoc($query);
                $result = $row;
            }

            if (!empty($result['pseudo_member'])) {

                $query2 = mysql_query("SELECT code_member from member WHERE pseudo_member = '$pseudo'") or die("Impossible de sélectionner l'email : " . mysql_error());

                if (mysql_num_rows($query2) == 1) {
                    $row = mysql_fetch_assoc($query2);
                    $result2 = $row;
                }

                if ((!empty($result2['code_member'])) AND ($result2['code_member'] == $code)) {
                    mysql_query("UPDATE member SET code_member = 0, level_member = 1 WHERE pseudo_member =  '$pseudo'");
                    $data['result'] = "<h2>Votre inscription est maintenant terminée.</h2><br>";
                    $data['result'] .= "<p>Vous pouvez vous connecter sur Meetoparty et profitez des avantages du site.</p><br>";
                    header('Refresh: 7; url=/index');
                } else {
                    $data['error']['wrong'] = "Il y a eu un problème lors de votre inscription.<br> Veuillez contacter les webmaster du site";
                }
            } else {
                $data['error']['wrong'] = "Il y a eu un problème lors de votre inscription.<br> Veuillez contacter les webmaster du site";
            }
        }

        return $data;
    }

    public function resendValidationEmail() {

        if (isset($this->user->email_member)) {
            $this->user->sendValidationMail($this->user->email_member);
            $data["result"] = "<p>Vous allez bientôt recevoir un mail de validation pour activer vore compte.</p><br>";
            $data["result"] .= "<p>Vous allez être redirigé automatiquement dans 5 secondes vers l'accueil du site.</p>";
        }
        else
            $data["result"] = "<p>please login first</p>";
        return $data;
    }

}

?>