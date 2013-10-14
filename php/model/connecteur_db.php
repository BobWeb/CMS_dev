<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of connecteur_dbc
 *
 * @author FTREBOIT
 */

if(!defined('SGBD_SERVEUR')) {
    require_once("config.php");
}

class connecteur_db extends mysqli {

    protected $id_connection;
    protected $nom_database;

    
    public function __construct() {
        $this->connect(SGBD_SERVEUR, SGBD_USER, SGBD_PSWD, SGBD_NOM);
        if(mysqli_connect_error()) {
            die("Connect Error (".mysqli_connect_errno().") ".mysqli_connect_error());
        }
        
        $this->query("SET CHARACTER SET utf8");
        $this->query("SET NAMES utf8");
        
        $this->nom_database = SGBD_NOM;
    }
    
    public function init_db(){
        
    }

    public function backupTable($table) {
        if($table != "") {
            $local_dir  = BACKUP_DIR;
            $file_name  = $table."-".gmdate("Y-m-d H:i:s").".sql";
            $command    = "mysqldump --host=".SGBD_SERVEUR." --user=".SGBD_USER." --password=".SGBD_PSWD;
            $command   .= " --skip-opt --compress --add-locks --create-options --disable-keys --quote-names --quick --extended-insert --complete-insert --default-character-set=latin1 --compatible=mysql40 --result-file='".$local_dir.$file_name."'";
            $command   .= " ".SGBD_NOM;
            $command   .= " ".$table;

            system($command);
        } else {
            new log("Aucun nom de table passé en paramètre, sauvegarde impossible.", true, 3);
        }
    }
    
    public function compareToTemp($table_name) {
        $requete = "DESCRIBE `".$table_name."`";
        $table = $this->execute_requete($requete);
        $requete = "DESCRIBE `".$table_name."_tmp`";
        $table_tmp = $this->execute_requete($requete);
        ///print_r($table_tmp);
        $ret = true;
        foreach($table as $key => $field) {
            if(!isset($table_tmp[$key]["Field"]) || $field["Field"] != $table_tmp[$key]["Field"]) {
                $ret =  false;
            }
        }
        return $ret;
    }
    
    public function dump($table){
        $sql = 'SHOW CREATE TABLE '.$table;
        $res = $this->requete($sql);
        if ($res) {
            $backup_file = '../../tmp/backup_' . $table . '.sql';
            $fp = fopen($backup_file, 'w');

            $tableau = mysql_fetch_array($res);
            $tableau[1] .= ";\n";
            $insertions = $tableau[1];
            fputs($fp,$insertions);

            $res_table = $this->query('SELECT * FROM '.$table);
            //$nbr_champs = mysql_num_fields($req_table);
            $nbr_champs = $res_table->field_count;
            
            while ($ligne = mysql_fetch_array($res_table)) {
                $insertions = 'INSERT INTO '.$table.' VALUES (';
                for ($i=0; $i<$nbr_champs; $i++) {
                    $insertions .= '\'' . $this->db->real_escape_string($ligne[$i]) . '\', ';
                }
                $insertions = substr($insertions, 0, -2);
                $insertions .= ");\n";
                fputs($fp,$insertions);
            }
        }
        mysql_free_result($res);
        fclose($fp);
        return true;
    }

    public function deconnection(){
        //mysql_close($this->id_connection);
    }
    
    public function requete($requete){
        $res = $this->query($requete);
        //echo "<br />-->".$requete."<--";
        //var_dump($res);
        return $res;
    }
    
    public function fetch_array($resultat, $result_type = MYSQL_BOTH){
        ////var_dump($resultat);
        if($resultat !== false) {
            $f = $resultat->fetch_array($result_type);
            return $f;
        } else {
            return false;
        }
    }
    
    public function execute_requete($requete, $result_type = MYSQL_BOTH) {
        //echo $requete;

        $retour = array();
        $resultat = $this->requete($requete);
        while(($ligne = $this->fetch_array($resultat, $result_type))) {
            $retour[] = $ligne;
        }
        return $retour;
    }
    
    public function getIdLastInsert(){
        return $this->insert_id;
    }
    
    public function getId_connection() {
        return $this->id_connection;
    }
    
    public function setId_connection($id_connection) {
        $this->id_connection = $id_connection;
    }

    /*public function exec_multi_query($requete) {
        $no_error = true;
        $res = $this->multi_query($requete);
        if($this->error) {
            echo "<br />".$this->error;
            $no_error = false;
        }
        if($res) {
            do {
                //   $this->next_result();
            } while ($this->next_result());
        } else {
            $no_error = false;
        }
        return $no_error;
    }*/
    
    public function exec_multi_query($requete) {
        $no_error = true;
        $requetes = explode(";", $requete);

        //print_r($requetes);
        //echo NEW_LINE.$requete.NEW_LINE;
        //echo NEW_LINE."nb_requetes:".$nb_requetes." ".NEW_LINE;
        $result_ok = 0;
        $res = $this->multi_query($requete);
        if($this->errno) {
            //echo "\n\n-->:".$requetes[0].":<--\n\n";
            throw new Exception("Erreur lors de l'execution d'une requête : ".$this->error." lors de l'execution de : ".$requetes[0]);
            $no_error = false;
        }
        $i = 0;
        if($res) {
            do {
                $i++;
            } while ($this->next_result());
            if ($this->errno) {
                //echo "\n\n-->--:".$requetes[$i].":<--\n\n";
                //echo "Stopped while retrieving result : ".$this->error;
                throw new Exception("Erreur lors de l'execution d'une requête : ".$this->error." lors de l'execution de : ".$requetes[$i]);
                $no_error = false;
            }
        } else {
            $no_error = false;
        }
        return $no_error;
    }

}
?>
