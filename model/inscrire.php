<?php

$email = giveInputString($_POST, 'email');
$nom = giveInputString($_POST, 'nom');
$mdp = giveInputString($_POST, 'mdp');
$id_camp = (int) giveInputString($_POST, 'camp');

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
elseif(!Camp::estJouable($id_camp)){
    $erreur = 'Camp invalide';
}
else{
    $Joueur = Joueur::inscrire(
        array(
            'nom' =>$nom,
            'mdp' =>Tools::crypte($mdp),
            'email' =>$email,
            'id_camp' =>$id_camp,
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
    $Joueur->ouvrirSession();
    header('Location: index.php?p=tableauBord');
}
exit();
?>