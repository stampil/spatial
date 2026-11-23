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
if(!is_string($p) || !preg_match(PAGE_NAME_PATTERN, $p)){
    $p = '404';
}

if(empty($_SESSION['Sid']) && !in_array($p, PUBLIC_PAGES, true)){
    header('Location: ?p=connection');
    exit();
}

if(!empty($_SESSION['Sid'])){
        try{
            $Joueur= new Joueur($_SESSION['Sid']);
        }
        catch(UnexpectedValueException $exception){
            session_destroy();
            header('Location: ?p=connection');
            exit();
        }
        $Joueur->resolveEvents();
        $etape_tuto = $Joueur->etape_tuto;
        if($etape_tuto==0 && $p=="listePerso"){
            $etape_tuto=1;
            $Joueur->etape_tuto=$etape_tuto;
            $Joueur->save();
        }
        elseif($etape_tuto==0 && $p!=LOGOUT_PAGE){
            $p = 'tuto_'.$Joueur->etape_tuto;
            $Perso = $Joueur->giveMainPerso();
        }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@400;500&display=swap">
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <link rel="stylesheet" href="css/md3.css" type="text/css">
        <?php
        if(is_file('css/'.$p.'.css')){
        ?>
            <link rel="stylesheet" href="css/<?=$p?>.css" type="text/css">
        <?php } ?>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="js/jquery.countdown.min.js"></script>
        <script src="js/main.js"></script>
                <?php
        if(is_file('js/'.$p.'.js')){
        ?>
            <script src="js/<?=$p?>.js"></script>
        <?php } ?>
        <title>
            <?php echo $p; ?>
        </title> 
    </head>
    <body>
    <?php require 'inc/menu.php'; ?>
    <main class="md3-page">
    <?php
    foreach(Tools::takeMessages() as $message){
        Tools::show_info_html($message);
    }

    try{
        if(is_file('model/'.$p.'.php')){
            require 'model/'.$p.'.php';
        }
        else{
            require 'model/404.php';
        }
    }
    catch(UnexpectedValueException $exception){
        echo $exception->getMessage();
    }
    ?>
    </main>

    </body>
</html>
