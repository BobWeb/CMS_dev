<?php
$html_content = '';

$blocks = new block();
$blocksPage = $blocks->getBlockByPage($infos_page[0]->id);

if(!empty($blocksPage)){
    $aBlocks = array();
    foreach($blocksPage as $b){
        $aBlocks[$b->zone_activite][] = $b;
    }
    $html_content .= $blocks->getHTMLByBlocks($aBlocks);
} else {
    ob_start();
    include_once("maintenance.php");
    $html_content .= ob_get_contents();
    ob_clean();
}
?>