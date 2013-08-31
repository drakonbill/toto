<?php

class controllers_api_index extends Controller {

    public function init() {
        
    }

    public function indexAction() {

        //example = list all API resuorces in json and link
        $coreInfo = new coreInfo();
        $resource = array();
        foreach ($coreInfo->getControllersList("api/") as $res) {
            $a = explode("controllers_api_", $res);
            $resource["resource"]["url"] = $a[1];
           
            echo "<a href='$a[1]'>api/$a[1]</a><br/>";
        }
        echo json_encode($resource);
        
    }

    public function indexGetAction() {
        $this->indexAction();
    }

}

?>
