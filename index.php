<?php
require_once 'inc/header.php';
if(Installation::ancienneBase()){
    require 'inc/migration.php';
    exit();
}
Installation::verifier();

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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@400;500&display=swap">
        <link rel="stylesheet" href="<?= Vue::ressource('css/main.css') ?>" type="text/css">
        <link rel="stylesheet" href="<?= Vue::ressource('css/md3.css') ?>" type="text/css">
        <link rel="stylesheet" href="<?= Vue::ressource('css/jeu.css') ?>" type="text/css">
        <?php
        if(is_file('css/'.$p.'.css')){
        ?>
            <link rel="stylesheet" href="<?= Vue::ressource('css/'.$p.'.css') ?>" type="text/css">
        <?php } ?>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="<?= Vue::ressource('js/jquery.countdown.min.js') ?>"></script>
        <script src="<?= Vue::ressource('js/main.js') ?>"></script>
        <script src="<?= Vue::ressource('js/rapport.js') ?>" defer></script>
        <?php if(isset($Joueur)){ ?>
        <script src="<?= Vue::ressource('js/musique.js') ?>" defer></script>
        <?php } ?>
                <?php
        if(is_file('js/'.$p.'.js')){
        ?>
            <script src="<?= Vue::ressource('js/'.$p.'.js') ?>" defer></script>
        <?php } ?>
        <title><?= htmlspecialchars(APP_TITLE.(isset(PAGES_TITRES[$p]) ? ' · '.PAGES_TITRES[$p] : '')) ?></title>
    </head>
    <body<?= isset($Joueur) && $Joueur->etape_tuto != 0 ? ' class="md3-avec-barre"' : '' ?>>
    <?php
    require 'inc/menu.php';
    if(isset($Joueur) && $Joueur->etape_tuto >= Tuto::ETAPE_USINE){
        require 'inc/hud.php';
    }
    ?>
    <main class="md3-page">
    <?php
    if(isset($Joueur) && $Joueur->etape_tuto >= Tuto::ETAPE_USINE && $Joueur->enTuto()){
        require 'inc/tuto.php';
    }
    $messages = Tools::takeMessages();
    if(count($messages) > 0){
        echo '<div class="messages" role="status" aria-live="polite">';
        foreach($messages as $message){
            Tools::show_info_html($message);
        }
        echo '</div>';
    }
    foreach(Tools::takeRapports() as $rapport){
        require 'inc/rapport.php';
    }
    echo Vue::bandeau('page/'.$p);

    try{
        if(is_file('model/'.$p.'.php')){
            require 'model/'.$p.'.php';
        }
        else{
            require 'model/404.php';
        }
    }
    catch(UnexpectedValueException $exception){
        Tools::show_error_html($exception->getMessage());
    }
    ?>
    </main>

    </body>
</html>
