<?php
require_once 'inc/header.php';

if(!empty($_GET['d'])){
    session_destroy();
}

if(!empty($_GET['p'])){
    $p = $_GET['p'];
}
else{
    $p = 'welcome';
}

if(!empty($_SESSION['Sid'])){
        $Joueur= new Joueur($_SESSION['Sid']);
        $etape_tuto = $Joueur->etape_tuto;
        if($etape_tuto==0 && $p=="listePerso"){
            $etape_tuto=1;
            $Joueur->etape_tuto=$etape_tuto;
            $Joueur->save();
        }
        elseif($etape_tuto==0){
            $p = 'tuto_'.$Joueur->etape_tuto;
            $Perso = new Perso(1);
        }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <?
        if(is_file('css/'.$p.'.css')){
        ?>
            <link rel="stylesheet" href="css/<?=$p?>.css" type="text/css">
        <? } ?>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="js/main.js"></script>
                <?
        if(is_file('js/'.$p.'.js')){
        ?>
            <script src="js/<?=$p?>.js"></script>
        <? } ?>
        <title>
            <?php echo $p; ?>
        </title> 
    </head>
    <body>
    <?php

    if(is_file('model/'.$p.'.php')){
        require 'model/'.$p.'.php';
    }
    else{
        require 'model/404.php';
    }
    ?>
<script>
  $(document).ready(function() {
    console.info('Jquery ready');  
  });
</script>
    </body>
</html>
