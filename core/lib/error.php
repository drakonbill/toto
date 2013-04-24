<?php

/**
 * Description of error
 *
 * @author Miki
 */
class error {

    public static function f404Static($e){
        if(!headers_sent()){
            header("HTTP/1.0 404 Not Found");
        }
        global $reg;
        $data = "ERROR";
        $conf = $reg->appconf;
        $user = $reg->user;
         if($reg->user->loggedin()) include("../" . APPDIR . "views/header_co.phtml");
         else include("../" . APPDIR . "views/header.phtml");
         
         $data = "<h1>File, Action Or Controll Not Found</h1><p>Error 404 Static</p> <p>Sorry the page your looking for dose not exsits or has been moved<br />
            if you think you seeing this page in error please contact the site administrator
			<br/>
			<br/>ERROR DATA:<br/>
			".$e."
            </p><hr />
            <em>".$_SERVER['SERVER_SOFTWARE']." </em>";
         
         include("../" . APPDIR . "views/404View.phtml");
         include("../" . APPDIR . "views/footer.phtml");
			
    }
}

?>
