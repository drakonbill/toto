<?php

class models_photo extends Model {

// No need for the moment
    public function indexModel() {
        
    }
    
    public function loadAlbum() {
        $request = "SELECT id_event,name_event,image_event FROM event WHERE id_member='".$_SESSION['id_member']."'";
        $request_exec = mysql_query($request);
        $tab_album = array();
        while($data_request = mysql_fetch_array($request_exec))
                $tab_album[] = $data_request;
        
        return $tab_album;
    }
}

?>
