<?php
    /*
     * It may be wortwhile developing a directory scanner to automatically load
     * all files within the classlib and config directories. This of course
     * would be better implemented using lazy loading.
     * A decision to be made */
    
    //Classes
    require_once('ldap.php');
    
    //Config
    require_once('../config/config_ldap.php');
    global $config;