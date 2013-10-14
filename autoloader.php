<?php

function t_autoload($class_name) {
    if((php_sapi_name() == 'cli') || (defined('AJAX')) || (defined('TOOL'))){
		$dir = ROOT_DIR.'/';
    } else {
        $dir = '';
    }
    
    if(is_file($dir."php/model/".$class_name.".php")) {
        include_once($dir."php/model/".$class_name.".php");
    }
    if(is_file($dir."php/service/".$class_name.".php")) {
        include_once($dir."php/service/".$class_name.".php");
    }
}

spl_autoload_register('t_autoload');
?>