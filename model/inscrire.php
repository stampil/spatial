<?php

if(!empty($_POST['nom']) && !empty($_POST['mdp']) && !empty($_POST['email'])){
    $id_joueur = Joueur::inscrire(
        array(
            'nom' =>$_POST['nom'],
            'mdp' =>Tools::crypte($_POST['mdp']),
            'email' =>$_POST['email'],
        )        
    );  
    header('Location: ?p=connection');
}
?>