<?php

    class common_model {
        
        protected static    $db_s = NULL;
        protected           $db;
        protected           $db_table;
        protected           $data;
        
        public function __construct($db_table) {
            if(self::$db_s == NULL) {
                $this->db = new connecteur_db();
                self::$db_s = $this->db;
            } else {
                $this->db = self::$db_s;
            }
            $this->db_table = $db_table;
            if(isset($this->db_table) == false || ($this->db_table == "")) {
                throw new Exception("The property \"db_table\" has not been set during the implementation of the model's constructor.");
            }
        }
        
        public function __destruct() {
            //echo "<br />Thread : ".$this->db->thread_id;

        }

        public function kill() {
            $thread = $this->db->thread_id;
            $this->db->requete("KILL QUERY ".$thread);
            //$res = $this->db->requete("KILL ".$thread);
            $res = $this->db->kill($thread);
            $this->db->close();
            //return $thread;
        }
        
        public function findAll($order_by = "id DESC", $start = 0, $limit = null) {
            $retour = array();
            $requete = "SELECT * 
                        FROM ".$this->db->real_escape_string($this->db_table)." ";
            
            if($order_by != null) {
                $requete.= " ORDER BY ".$this->db->real_escape_string($order_by)." ";
            }
            if($limit != null) {
                $requete.= " LIMIT ".$this->db->real_escape_string($start).", ".$this->db->real_escape_string($limit);
            }
            
            $resultat = $this->db->requete($requete);
            while(($db_set = $this->db->fetch_array($resultat))) {
                $retour[] = $this->loadDataFromArray($db_set);
            }
            return $retour;
        }
        
        public function findBy($key, $value, $order_by= "id DESC", $start = 0, $limit = 30, $group_by = "") {
            $retour = array();
            $requete = "SELECT * 
                        FROM ".$this->db->real_escape_string($this->db_table)." 
                        WHERE ".$this->db->real_escape_string($key)." = '".$this->db->real_escape_string($value)."' ";

            if($group_by != "") {
                $requete.= " GROUP BY ".$this->db->real_escape_string($group_by);
            }
            
            $requete.= " ORDER BY ".$this->db->real_escape_string($order_by)." ";
            
            if($limit != false) {
                $requete.= " LIMIT ".$this->db->real_escape_string($start).", ".$this->db->real_escape_string($limit);
            }
            
            $resultat = $this->db->requete($requete);
            while(($db_set = $this->db->fetch_array($resultat))) {
                $retour[] = $this->loadDataFromArray($db_set);
            }
            return $retour;
        }
        
        public function loadDataFromArray($db_set) {
            $class_name = get_called_class();
            $obj = new $class_name();
            
            if(is_array($db_set)) {
                foreach($db_set as $key => $value) {
                    if(!is_int($key)) {
                        $obj->__set($key, $value);
                    }
                }
            }
            return $obj;
        }
        
        public function loadById($id) {
            
            $requete = "SELECT * 
                        FROM ".$this->db->real_escape_string($this->db_table)." 
                        WHERE id = '".$this->db->real_escape_string($id)."'";
            //echo "<br />".$requete."<br />";
            $resultat = $this->db->requete($requete);
            $db_set = $this->db->fetch_array($resultat);

            if($db_set) {
                foreach($db_set as $key => $value) {
                    if(!is_int($key)) {
                        $this->__set($key, $value);
                    }
                }
            } else {
                return false;
            }
        }
        
        public function __set($key, $value) {
            $this->data[$key] = $value;
        }

        public function __get($key) {
            return $this->data[$key];
        }
        
        public function __unset($key) {
            unset($this->data[$key]);
        }
        
        public function __call($name, $arguments) {
            // Note: value of $name is case sensitive.
            $key = strtolower(str_replace("findBy", "", $name));
            $value = $arguments[0];
            if(isset($arguments[1])) {
                $order = $arguments[1];
            } else {
                $order = "id";
            }
            if(isset($arguments[2])) {
                $start = $arguments[2];
            } else {
                $start = 0;
            }
            if(isset($arguments[3])) {
                $limit = $arguments[3];
            } else {
                $limit = 30;
            }                 
            if(isset($arguments[4])) {
                $group_by = $arguments[4];
            } else {
                $group_by = "";
            }        
            //echo "Calling object method findBy('".$key."', '".$value."');";
            return $this->findBy($key, $value, $order, $start, $limit, $group_by);
        }
        
        public function __toString() {
            //echo "plop"; exit;
            $str = "".get_called_class()." : ";
            $str.= "<pre>".print_r($this->data, true)."</pre>";
             
            return $str;
        }
                
        public function __toArray() {
            return $this->data;
        }
        
        public function __isset($key) {
            if(isset($this->data[$key])) {
                return true;
            } else {
                return false;
            }
        }
        
        public function indice_isset($key, $indice) {
            if(is_array($this->data[$key]) && isset($this->data[$key][$indice])) {
                return true;
            } else {
                return false;
            }
        }
        
        public function insert($force_ignore = false) {
            foreach($this->data as $key => $value) {
                if($key != "id") {
                    $clean_keys []     = "`".$this->db->real_escape_string($key)."`";
                    $clean_datas[$key] = $this->db->real_escape_string($value);
                }
            }
            
            $keys = implode(", ", $clean_keys);
            $datas = "'".implode("', '", $clean_datas)."'";
            $requete = "INSERT ";
            
            if($force_ignore) {
                $requete.= " IGNORE ";
            }
            $requete.= " INTO `".$this->db->real_escape_string($this->db_table)."` (".$keys.") VALUES (".$datas.") ";
            // echo $requete;
            $res = $this->db->requete($requete);
            
            $this->id = $this->db->getIdLastInsert();
            
            return $res;
        }
        
        public function update() {
            $requete = "UPDATE ".$this->db->real_escape_string($this->db_table)." SET ";
            $r = array();
            foreach($this->data as $key => $value) {
                //echo "\nkey:".$key." - value:".$value;
                if($key != "id" && !is_null($value)) {
                    $r[] = "`".$this->db->real_escape_string($key)."` = '".$this->db->real_escape_string($value)."'";
                }
            }
            $requete.= implode(", ", $r)." WHERE id = '".$this->db->real_escape_string($this->id)."' ";
            // echo $requete;
            return $this->db->requete($requete);
        }
        
        public function save($force_ignore = false) {
            if(isset($this->id)) {
                return $this->update();
            } else {
                return $this->insert($force_ignore);
            }
        }
        
        public function delete() {
            if (isset($this->id)) {
                $requete = "DELETE 
                            FROM ".$this->db->real_escape_string( $this->db_table )." 
                            WHERE id = ".$this->db->real_escape_string($this->id)."";

                return $this->db->requete($requete);
            } else {
                return false;
            }
        }

        /**
        * Chargement automatique d'un objet
        */

        public function loadMeWith( array $dbSet )
        {
            foreach ( $dbSet as $key => $value )
            {
                if ( !is_int( $key ) )
                {
                    $this->__set( $key, $value );
                }
            }

            return( $this );
        }

        /**
        * Vérification de l'existence d'une valeur
        */

        public function valueExists( $field, $value = '' )
        {
            if ( empty( $value ) )
            {
                $value = ( isset( $this->$field ) ? $this->$field : '' );
            }

            if ( !empty( $value ) )
            {
                $res = $this->{'findBy'.ucfirst( $field )}( $value );

                if ( !empty( $res ) )
                {
                    return( true );
                }
            }

            return( false );
        }

        /**
        * Récupération des valeurs d'un objet
        */

        public function getData()
        {
            return( $this->data );
        }

        /**
        * Suppression infos utilisateur
        */


        /**
        * prepositionnement des valeurs d'un objet
        */

        public function setData($data) {
            $this->data = $data;
            return;
        }


        public function deleteByUserId( $itemId = '' )
        {
            $userId = $this->user_id;

            if ( !empty( $userId ) )
            {
                $sql =  "   DELETE
                            FROM        ".$this->db->real_escape_string( $this->db_table ).
                        "   WHERE       user_id = ".$this->db->real_escape_string( $userId );

                if ( !empty( $itemId ) )
                {
                    $sql .= " AND id = ".$this->db->real_escape_string( $itemId );
                }

                return( $this->db->requete( $sql ) );
            }

            return( false );
        }
        
        public function lock() {
            $requete = "UNLOCK TABLES ";
            $this->db->requete($requete);
            $requete = "LOCK TABLE `".$this->db->real_escape_string( $this->db_table )."` WRITE ";
            $this->db->requete($requete);
        }
        
        public function clearTable() {
            $requete = "DELETE FROM `".$this->db->real_escape_string( $this->db_table )."` ";
            $this->db->requete($requete);
        }
        
        public function unlock() {
            $requete = "UNLOCK TABLES ";
            $this->db->requete($requete);
        }
        
        public function randomAll($nombre){
            $random = "";

            for($i = 1;$i <= $nombre;$i++){
                if(rand(1,2) == 1){
                    $random .= $this->randomString(1);
                } else {
                    $random .= $this->randomNumber(1);
                }
            }

            return $random;
        }

        public function randomString($nombre) {
            $arrayChar = array("a","z","e","r","t","y","u","i","o","p","q","s","d","f","g","h","j","k","l","m","w","x","c","v","b","n");
            $random = "";

            for($i = 1;$i <= $nombre;$i++){
                if(rand(1,2) == 1){
                    $random .= strtoupper($arrayChar[rand(0,count($arrayChar)-1)]);
                } else {
                    $random .= $arrayChar[rand(0,count($arrayChar)-1)];
                }
            }

            return $random;
        }

        public function randomNumber($nombre){
            $random = "";

            for($i = 1;$i <= $nombre;$i++){
                $random .= rand(0,9);
            }

            return $random;
        }
    }
?>