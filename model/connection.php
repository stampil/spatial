<?php
    $erreur = giveInputString($_GET, 'erreur');
    if($erreur !== ''){
        Tools::show_error_html($erreur);
    }
?>

<h1 class="titre-vue"><?= Icone::html('connexion') ?>Connexion</h1>

<form class="formulaire" method="POST" action="index.php?p=connecter">

<div class="md3-field"><label for="nom">Nom du joueur</label><input type="text" id="nom" name="nom" required /></div>
<div class="md3-field"><label for="mdp">Mot de passe</label><input type="password" id="mdp" name="mdp" required/></div>
<input type="submit" class="md3-button md3-button--grand" value="Se connecter" name="go" />
</form>
