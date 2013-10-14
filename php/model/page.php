<?php

class page extends common_model {
    protected static $db_table_c = 'cms_page';

    public function __construct(){
        parent::__construct( self::$db_table_c );
    }
    
    public function getMeta($infos_page){
        $html_Meta = "";
        if(isset($infos_page[0]->meta_title) && !empty($infos_page[0]->meta_title)){
            $title = $infos_page[0]->meta_title;
        } else {
            $title = "Default Title";
        }
        if(isset($infos_page[0]->meta_description) && !empty($infos_page[0]->meta_description)){
            $description = $infos_page[0]->meta_description;
        } else {
            $description = "Default Description";
        }
        if(isset($infos_page[0]->meta_keywords) && !empty($infos_page[0]->meta_keywords)){
            $keywords = $infos_page[0]->meta_keywords;
        } else {
            $keywords = "Default Keywords";
        }
        $html_Meta .= $this->getMetaTitle($title)."\n";
        $html_Meta .= "\t".$this->getMetaDescription($description)."\n";
        $html_Meta .= "\t".$this->getMetaKeywords($keywords)."\n";
        return $html_Meta;
    }
    
    public function getMetaTitle($meta_title,$html = true){
        if($html){
            return '<title>'.$meta_title.'</title>';
        } else {
            return array("meta_title"=>$meta_title);
        }
    }
    public function getMetaDescription($meta_description,$html = true){
        if($html){
            return '<meta name="description" content="'.$meta_description.'" />';
        } else {
            return array("meta_description"=>$meta_description);
        }
    }
    public function getMetaKeywords($meta_keywords,$html = true){
        if($html){
            return '<meta name="keywords" content="'.$meta_keywords.'" />';
        } else {
            return array("meta_keywords"=>$meta_keywords);
        }
    }
    public function getLinkCSS($allCSS,$html = true){
        if($html){
            if(!empty($allCSS)){
                $display = "\t";
                foreach($allCSS as $css){
                    $display .= '<link rel="stylesheet" href="'.$css.'" />'.RET_CHAR;
                }
                return $display;
            } else {
                return '';
            }
        } else {
            return $allCSS;
        }
    }
    public function getLinkJS($allJS,$html = true){
        if($html){
            if(!empty($allJS)){
                $display = "\t";
                foreach($allJS as $js){
                    $display .= '<script type="text/javascript" src="'.$js.'"></script>'.RET_CHAR;
                }
                return $display;
            } else {
                return '';
            }
        } else {
            return $allJS;
        }
    }
}
?>	