<?php

$email = giveInputString($_POST, 'email');
$mdp = giveInputString($_POST, 'mdp');

if($email !== '' && $mdp !== ''){
    $info = Joueur::connecter(
        array(
            'mdp' =>$mdp,
            'email' =>$email,
        )        
    );  
    
    if($info){
        $Joueur = new Joueur($info->id);
        $Joueur->ouvrirSession();

        header('Location: index.php?p='.HOME_PAGE);
        exit();
    }
    else{
        header('Location: index.php?p=connection&erreur=Identifiants non reconnus');
        exit();
    }
}

?>