<?php

class access {

    private $acl = array();

    function __construct($user) {
        global $reg;
        
        //load role privilegias
        $sql = "select acl.controller, acl.accesslevel from accesslist acl, accesslist_roles aclr, roles r 
            where aclr.id_roles = r.id_roles 
            and aclr.id_accesslist = acl.id_accesslist 
            and r.id_roles = ".$user->id_roles;
        
        $query = mysql_query($sql, $reg->dbcon) or die(mysql_error());
        while ($row = mysql_fetch_assoc($query)) {
            foreach ($row as $value) {
               $this->add($row['controller'], $row['accesslevel']);
            }
        }
        
        //load user privilegias and override this set for roles
        
         $sql = "select acl.controller, acl.accesslevel from accesslist acl, accesslist_member aclm
            where aclm.id_accesslist = acl.id_accesslist 
            and aclm.id_member = ".$user->id_member;
        
        $query = mysql_query($sql, $reg->dbcon) or die(mysql_error());
        while ($row = mysql_fetch_assoc($query)) {
            foreach ($row as $value) {
               $this->add($row['controller'], $row['accesslevel']);
            }
        }
        
    }

    function add($class, $action) {
        $this->acl["controllers_".$class] = $action;
    }

    function inRole($class, $level) {
        debug("ACL ===> ".$class." ".$level);
        return isset($this->acl[$class])?$level <= $this->acl[$class]:$level <= LEVELS::DEF;
    }

}

?>