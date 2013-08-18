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
        $data["title"] = "ERROR ";
        $conf = $reg->appconf;
        $user = $reg->user;
         if($reg->user->loggedin()) include("../" . APPDIR . "views/header_co.phtml");
         else include("../" . APPDIR . "views/header.phtml");
         
         $data = "<h1>File, Action Or Controller Not Found</h1>
        <p>Error 404 !  =(</p>
        <p>Pardon, la page que vous recherchez n'existe pas, ou a été modifiée !
            <br />
            Si vous rencontrez des problèmes, ou que cette page devrait être encore là, envoyez-nous un email.
            <br/>
        </p>";
         $data .= $debug?"<br/>ERROR DATA:<br/>".$e:"";
         $data .= "
            </p>";
         
         include("../" . APPDIR . "views/errorPage.phtml");
         include("../" . APPDIR . "views/footer.phtml");
			
    }
    
        public static function f403Static($e){
        global $debug;
        if(!headers_sent()){
            header("HTTP/1.0 403 Forbidden");
        }
        global $reg;
        $data["title"] = "ERROR 403 Forbidden";
        $conf = $reg->appconf;
        $user = $reg->user;
         if($reg->user->loggedin()) include("../" . APPDIR . "views/header_co.phtml");
         else include("../" . APPDIR . "views/header.phtml");
         
         $data['error'] = "<h1> You do not have sufficient permissions to access this page</h1>
        <p>Error 403 !  =(</p>
        <p>Pardon, la page que vous recherchez n'existe pas, ou a été modifiée !
            <br />
            Si vous rencontrez des problèmes, ou que cette page devrait être encore là, envoyez-nous un email.
            <br/>
        </p>";
         $data['error'] .= $debug?"<br/>ERROR DATA:<br/>".$e:"";
         
         
         include("../" . APPDIR . "views/errorPage.phtml");
         include("../" . APPDIR . "views/footer.phtml");
			
    }
}

?>
