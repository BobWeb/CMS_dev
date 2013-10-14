<?php

$page = new page();

$page_working = str_replace("/","_",trim($_SERVER["REQUEST_URI"],"/"));

if($page_working == ""){
    $page_working = "home";
}

$infos_page = $page->findBy("url_rewritted",$page_working);

if(!empty($infos_page)){
    $meta_head = $page->getMeta($infos_page);
} else {
    errorService::rep404();
}

$ScriptStyle = array();
$ScriptStyle["js"] = array("js/lib/jquery.min.js","js/script.js");
$ScriptStyle["css"] = array("css/style.css");

?>