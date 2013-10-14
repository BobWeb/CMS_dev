<?php

class errorService {

    public static function rep404() {
        header("HTTP/1.0 404 Not Found", true, 404);

        ob_start();
        if(file_exists("404.php")){
            include "404.php";
        }
        $res = ob_get_contents();
        ob_end_clean();

        echo $res;
        exit;
    }
}

?>