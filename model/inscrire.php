<?php

$email = giveInputString($_POST, 'email');
$nom = giveInputString($_POST, 'nom');
$mdp = giveInputString($_POST, 'mdp');

$erreur = '';
if($email === '' || $nom === '' || $mdp === ''){
    $erreur = 'Tous les champs sont obligatoires';
}
elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $erreur = 'Email invalide';
}
elseif(strlen($mdp) < PASSWORD_MIN_LENGTH){
    $erreur = 'Mot de passe trop court';
}
else{
    $Joueur = Joueur::inscrire(
        array(
            'nom' =>$nom,
            'mdp' =>Tools::crypte($mdp),
            'email' =>$email,
        )        
    );  
    if($Joueur === null){
        $erreur = 'Email ou nom déjà utilisé';
    }
}

if($erreur !== ''){
    header('Location: ?p=inscription&erreur='.urlencode($erreur));
}
else{
    header('Location: ?p=connection');
}
exit();
?>