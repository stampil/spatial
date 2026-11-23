<?php

if(!empty($_POST['nom']) && !empty($_POST['mdp']) && !empty($_POST['email'])){
    $Joueur = Joueur::inscrire(
        array(
            'nom' =>$_POST['nom'],
            'mdp' =>Tools::crypte($_POST['mdp']),
            'email' =>$_POST['email'],
        )        
    );  
    if($Joueur === null){
        header('Location: ?p=inscription&erreur='.urlencode('email ou nom deja utilise'));
    }
    else{
        header('Location: ?p=connection');
    }
    exit();
}
?>