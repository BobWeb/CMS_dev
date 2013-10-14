<!DOCTYPE html>
<html>
    <head>
        <base href="<?php echo BASE_URL; ?>" />
        <meta charset="utf-8" />
        <meta name="robots" content="noindex" />
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
        <?php 
        echo $meta_head;
        echo $css;
        echo $js; ?>
    </head>
    <body>
        <?php echo $html_content; ?>
    </body>
</html>