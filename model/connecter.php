<?php

$nom = giveInputString($_POST, 'nom');
$mdp = giveInputString($_POST, 'mdp');

if($nom !== '' && $mdp !== ''){
    $info = Joueur::connecter(
        array(
            'mdp' =>$mdp,
            'nom' =>$nom,
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