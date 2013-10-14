<?php

class block extends common_model {
    protected static $db_table_c = 'cms_block';

    public function __construct(){
        parent::__construct( self::$db_table_c );
    }
    
    public function getBlockByPage($id){
        $retour = array();
        $sql = "SELECT * 
                FROM ".$this->db->real_escape_string($this->db_table)."
                WHERE `activite` = 1
                AND `page` = '".$this->db->real_escape_string($id)."' 
                AND (   (`date_debut` >= NOW() AND `date_fin` <= NOW()) 
                        OR (date_debut = '0000-00-00 00:00:00' AND date_fin = '0000-00-00 00:00:00'))";
        
        $resultat = $this->db->requete($sql);
        while(($db_set = $this->db->fetch_array($resultat))) {
            $retour[] = $this->loadDataFromArray($db_set);
        }
        return $retour;
    }
    
    public function getHTMLByBlocks($aBlocks = array()){
        $html = "";
        if(!empty($aBlocks)){
            $htmlHead = "";
            $htmlLeft = "";
            $htmlRight = "";
            $htmlFooter = "";
            $divCMS = '<div class="cms_block">';
            $endDivCMS = '</div>';
            foreach($aBlocks as $zone => $blocks){
                foreach($blocks as $block){
                    switch($zone){
                        case "1":
                            $htmlHead .= $divCMS.$this->getHTMLBlock($block).$endDivCMS;
                            break;
                        case "2":
                            $htmlLeft .= $divCMS.$this->getHTMLBlock($block).$endDivCMS;
                            break;
                        case "3":
                            $htmlRight .= $divCMS.$this->getHTMLBlock($block).$endDivCMS;
                            break;
                        case "4":
                            $htmlFooter .= $divCMS.$this->getHTMLBlock($block).$endDivCMS;
                            break;
                    }
                }
            }
            $html = $htmlHead.$htmlLeft.$htmlRight.$htmlFooter;
        }
        return $html;
    }
    
    public function getHTMLBlock($block){
        $html = "";
        if(!empty($block->nom_automate)){
            include("template/automate/".$block->nom_automate."/".$block->nom_automate."_controller.php");
            $bObjet = new $block->nom_automate();
            $html .= $bObjet->constructHTML();
        } else if(!empty($block->file_externe)){
            ob_start();
            include_once($block->file_externe);
            $html .= ob_get_contents();
            ob_clean();
        }
        return $html;
    }
}

?>