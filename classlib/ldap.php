<?php
class gsLDAP {
    
    private $server = "ldap://ldap.geeksoc.org";
    private $dn = "ou=People,dc=geeksoc,dc=org";
    
    private $con;
    
    private $last_user_object;
    
    public function __construct() {
        $this->connect();
    }
    
    public function user_get($sUser) {
        $user_search = ldap_search($con, $dn, "(uid=$sUser)");
        $this->last_user_object = ldap_get_entries($con, $user_search); 
        
        return $this->last_user_object;
    }
    
    private function connect() {
        $con = ldap_connect($this->server);
        ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
    }
}