<?php

/**
 * Description of error
 *
 * @author Miki
 */
class error {

    public static function f404Static($e){
        global $debug;
        if(!headers_sent()){
            header("HTTP/1.0 404 Not Found");
        }
        global $reg;
        $data = "ERROR";
        $conf = $reg->appconf;
        $user = $reg->user;
         if($reg->user->loggedin()) include("../" . APPDIR . "views/header_co.phtml");
         else include("../" . APPDIR . "views/header.phtml");
         
         $data = "<p>";
         $data= $debug?"<br/>ERROR DATA:<br/>".$e:"";
         $data .= "
            </p>";
         
         include("../" . APPDIR . "views/404View.phtml");
         include("../" . APPDIR . "views/footer.phtml");
			
    }
}

?>
