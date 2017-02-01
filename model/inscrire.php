<?php

if(!empty($_POST['nom']) && !empty($_POST['mdp']) && !empty($_POST['email'])){
    $id_joueur = Joueur::inscrire(
        array(
            'nom' =>$_POST['nom'],
            'mdp' =>Tools::crypte($_POST['mdp']),
            'email' =>$_POST['email'],
        )        
    );  
    //TODO creer systeme pour joueur $id_joueur
    echo 'joueur créé : '.$id_joueur;
}
?>