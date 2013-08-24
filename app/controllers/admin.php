<?php

class controllers_admin extends Controller {

    public function init() {
        
    }

    function indexAction() {
        global $_URL;
        if (isset($_URL["table"])) $table = $_URL["table"];
        else $table = "member";
        
        $coreInfo = new coreInfo();
        
        $form = $this->loadHelper("form", $table);
        $rows["email_member"] = "email_member";
        
        $loginForm = $form->generate($rows);
        $this->loadView("header_co", "");
        echo $loginForm;
        
        echo "<select>";
        foreach ($coreInfo->getControllersList() as $value) {
            echo "<option>$value</option>";
        }
        echo "</select>";
        
        
        $this->loadView("footer", "");
    }

}

?>
