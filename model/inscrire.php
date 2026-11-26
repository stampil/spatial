<?php

$nom = giveInputString($_POST, 'nom');
$mdp = giveInputString($_POST, 'mdp');
$id_camp = (int) giveInputString($_POST, 'camp');

$erreur = '';
if($nom === '' || $mdp === ''){
    $erreur = 'Tous les champs sont obligatoires';
}
elseif(!Camp::estJouable($id_camp)){
    $erreur = 'Camp invalide';
}
else{
    $Joueur = Joueur::inscrire(
        array(
            'nom' =>$nom,
            'mdp' =>Tools::crypte($mdp),
            'id_camp' =>$id_camp,
        )        
    );  
    if($Joueur === null){
        $erreur = 'Nom déjà utilisé';
    }
}

if($erreur !== ''){
    header('Location: ?p=inscription&erreur='.urlencode($erreur));
}
else{
    $Joueur->ouvrirSession();
    $jeton = $Joueur->creerJeton();
    require 'inc/memoriser.php';
}
exit();
?>