<?php

class models_index extends Model {

    function indexModel() {
        $data["annee"] = $this->annee();
        $data["mois"] = $this->mois();
        $data["jour"] = $this->jour();
        $data["token"] = $this->setToken();
        return $data;
    }

    function annee() {

        $data = "";

        $date = date("Y");
        $dateminimum = $date - 10;
        $datemax = $date - 90;

        for ($i = $dateminimum; $i >= $datemax; $i--) {

            $data .= "<option value=$i>$i</option>";
        }

        return $data;
    }

    function mois() {
        $data = "";
        for ($i = 1; $i <= 12; $i++) {

            $data .= "<option value=\"$i\">$i</option>";
        }
        return $data;
    }

    function jour() {
        $data = "";
        for ($i = 1; $i <= 31; $i++) {

            $data .= "<option value=\"$i\">$i</option>";
        }
        return $data;
    }

    // Fonction to set a new token
    function setToken() {

        // Token generation 
        $timestamp_old = time() - (10 * 60);

        // To avoid Bug with token
        if ((@$_SESSION['token_time'] < $timestamp_old) || (@empty($_SESSION['token']) && @empty($_SESSION['token_time']))) {
            // If the token has expiered 

            $token = uniqid(rand(), true);
            $_SESSION['token'] = $token;
            $_SESSION['token_time'] = time();
        }

        return $_SESSION['token'];
    }

}

?>
