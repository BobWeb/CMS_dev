<?php

class form {
    protected $criterias;
    
    public function __contruct(){
        $this->criterias = new criterias();
    }
    
    public function createStartForm($form,$method){
        $html .= '<form class="formulaire" method="'.$method.'">';
        $html .=    '<input type="hidden" name="formulaire" value="'.$form.'">';
        
        return $html;
    }
    
    public function createEndForm(){
        $html = '</form>';
        return $html;
    }
    
    public function createLibelleBlock($libelle){
        return '<h3>'.$libelle.'</h3>';
    }
    
    public function createChamps($type,$name,$libelle,$obligatoire = false,$error = false){
        $html = '<div class="row"><label>';
        $html .=    '<div class="libelle">'.$libelle.(($obligatoire)?' *':'').'</div>';
        $html .=    '<input type="'.$type.'"'.(($obligatoire)?' required="true"':'').' name="'.$name.'" placeholder="'.$libelle.'" value="'.((isset($this->criterias->$name))?$this->criterias->$name:'').'"'.(($error)?' style="padding:9px;border:solid 2px red;"':'').' />';
        $html .=    ($obligatoire)?' <img class="status" align="center" width="20" height="20" src="img/vignette_nok.png" alt="status_champ"'.(($error)?' style="display:inline-block;"':'').' />':'';
        $html .= '</label></div>';
        return $html;
    }
    
    public function createTextarea($name,$libelle,$obligatoire = false,$error = false){

        $html = '<div class="row"><label>';
        $html .=    '<div class="libelle">'.ucfirst($name).(($obligatoire)?' *':'').'</div></label>';
        $html .=    '<textarea align="right"'.(($obligatoire)?' required="true"':'').' name="'.$name.'" placeholder="'.$libelle.'"'.(($error)?' style="padding:9px;border:solid 2px red;"':'').'>'.((isset($this->criterias->$name))?$this->criterias->$name:false).'</textarea>';
        $html .=    ($obligatoire)?' <img class="status" align="center" width="20" height="20" src="img/vignette_nok.png" alt="status_champ"'.(($error)?' style="display:inline-block;"':'').' />':'';
        $html .= '</div>';
        return $html;
    }
    
    public function createRadio($libelle,$choix = array(),$obligatoire = false){
        $html = '<div class="row">';
        $html .=    '<label class="">';
        $html .=        '<div class="libelle">'.ucfirst($libelle).(($obligatoire)?' *':'').'</div>';
        $html .=    '</label>';
        if(!empty($choix)){
            foreach($choix as $k => $v){
        $html .=    '<label class="checkbox_monochoice">';
        $html .=    '<input type="radio" value="'.$k.'" name="'.$v["name"].'" /> ';
        $html .=    '<span class="rounded"></span> '.$v["libelle"];
        $html .=    '</label>';
            }
        }
        $html .= '</div>';
        return $html;
    }
    
    public function createCheckboxDuo($name,$libelle,$libelleTrue,$libelleFalse){
        $html = '<div class="row">';
        $html .=    '<label class="checkbox_duochoice">';
        $html .=        '<div class="libelle">'.ucfirst($libelle).'</div>';
        $html .=        '<span class="switch">';
        $html .=            '<input type="checkbox" name="'.$name.'" />';
        $html .=            '<span class="switch-container">';
        $html .=                '<span class="on">'.$libelleTrue.'</span>';
        $html .=                '<span class="mid"></span>';
        $html .=                '<span class="off">'.$libelleFalse.'</span>';
        $html .=            '</span>';
        $html .=        '</span>';
        $html .=    '</label>';
        $html .= '</div>';
        return $html;
    }
    
    public function createSubmit($libelle){
        $html = '<input type="submit" value="'.$libelle.'" />';
        return $html;
    }
}

?>