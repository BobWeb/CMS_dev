<?php
    if (!defined('DOC_ROOT')) {
        define( 'DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] );
    }
    
    if($_SERVER["HTTP_HOST"] != "localhost" && $_SERVER["HTTP_HOST"] != "127.0.0.1"){
        define("ROOT_DIR", "/");
        define("BASE_URL", "http://".$_SERVER["HTTP_HOST"]."/");
    } else {
        define("ROOT_DIR","C:/wamp/www/CMS_dev/");
        define("BASE_URL", "http://www.cms.local/");
    }
    
    if(!defined('SGBD_SERVEUR')) {
        define("SGBD_SERVEUR",  "localhost");
        define("SGBD_NOM",      "cms_dev");
        define("SGBD_USER",     "root");
        define("SGBD_PSWD",     "root");
    }
    
    if(php_sapi_name() != 'cli') {
        define("NEW_LINE", "<br />");
    } else {
        define("NEW_LINE", "\n");
    }
    define("RET_CHAR","\n\t");
    
    define("SMTP_HOST_NAME", "");
    
    define("FROM_MAIL","");
    
    define("IMG_URL",BASE_URL."img");
    define("JS_URL",BASE_URL."js");
    define("CSS_URL",BASE_URL."css");
    define("AJAX_URL",BASE_URL."ajax");
    
    define("INCLUDES_PATH",ROOT_DIR."php/include/");
    define("TEMPLATE_PATH",ROOT_DIR."template/");
    define("AUTOMATE_PATH",ROOT_DIR."template/automate/");
?>