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
			echo "<title>File, Action Or Controll Not Found</title><h1>Error 404 Static</h1> <p>Sorry the page your looking for dose not exsits or has been moved<br />
            if you think you seeing this page in error please contact the site administrator
			<br/>
			<br/>ERROR DATA:<br/>
			".$e."
            </p><hr />
            <em>".$_SERVER['SERVER_SOFTWARE']." </em>";
    }
}

?>
