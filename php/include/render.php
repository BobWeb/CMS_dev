<?php

// Gestion des données relatives à la page
include_once(INCLUDES_PATH."infos_page.php");

// Gestion des données relatives aux blocks à intégrer
include_once(INCLUDES_PATH."blocks.php");

$css = $page->getLinkCSS(array_unique($ScriptStyle["css"]));
$js = $page->getLinkJS(array_unique($ScriptStyle["js"]));

?>