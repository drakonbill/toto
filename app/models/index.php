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

    function setToken(){
        
         
                // Génération des tokens
                $token = uniqid(rand(), true);
                $_SESSION['token'] = $token;
                $_SESSION['token_time'] = time();
         
             return $token;   
        
    }
}

?>
