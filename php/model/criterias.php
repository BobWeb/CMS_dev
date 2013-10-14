<?php

    class criterias extends common_model {
        
        protected static $db_table_c = "criteria";
        
        public function __construct() {
            parent::__construct(self::$db_table_c);
            
            foreach($_POST as $key => $param) {
                $this->$key = $param;
            }
            foreach($_GET as $key => $param) {
                $this->$key = $param;
            }
        }
        
        public function urldecode() {
            foreach($this->data as $key => $d) {
                if(is_string($d)) {
                    $this->data[$key] = urldecode($d);
                }
            }
        }

        public function urlencode() {
            foreach($this->data as $key => $d) {
                if(is_string($d)) {
                    $this->data[$key] = urldecode($d);
                }
            }
        }
        
        public function toUrl($update_key = "", $update_value = "", $file = "") {
            $url = $file."?";
            $data = $this->__toArray();
            //print_r($data);
            $update_param_used = false;
            
            if(is_array($data)) {
                foreach($data as $key => $value) {
                    if($key == $update_key) {
                        $value = $update_value;
                        $update_param_used = true;
                    }

                    if(!is_array($value)) {
                        $url.= $key."=".$value."&";
                    } else {
                        foreach($value as $k => $v) {
                            $url.= $key."[".$k."]=".$v."&";
                        }
                    }
                }
            }
            
            if(!$update_param_used && $update_key != "") {
                $url.= $update_key."=".$update_value."&";
            }
            
            return substr($url, 0, -1);
        }
        
        public function isChecked($key, $index, $value, $string = "class='selected'") {
            if($index != "") {
                $res = $this->$key;
                if(isset($res[$index]) && strtolower($res[$index]) == strtolower($value)) {
                    return " ".$string;
                }
            } else {
                if(isset($this->$key) && strtolower($this->$key) == strtolower($value)) {
                    return " ".$string;
                }
            }
        }
        
        public function isSelected($key, $value, $string = "class='selected'") {
            if($this->$key == $value) {
                return " ".$string;
            }
        }
        
        public function __get($key) {
            if(isset($this->$key)) {
                return parent::__get($key);
            } else {
                return "";
            }
        }
    }
?>
