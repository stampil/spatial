<?php
require_once 'inc/header.php';

if(!empty($_GET['p'])){
    $p = $_GET['p'];
}
else{
    $p = 'affichePlanete';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <title>
            <?php echo $p; ?>
        </title> 
    </head>
    <body>
    <?php
    require 'model/'.$p.'.php';
    ?>
    </body>
</html>
