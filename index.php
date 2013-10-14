<?php
include_once("config.php");
include_once("autoloader.php");

sessionStorage::getInstance();

$criterias = new criterias();

include_once(INCLUDES_PATH."render.php");

ob_start();
include_once(TEMPLATE_PATH."view.tpl");
$display = ob_get_contents();
ob_clean();
    
echo $display; 
?>