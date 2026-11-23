<?php
    $erreur = giveInputString($_GET, 'erreur');
    if($erreur !== ''){
        Tools::show_error_html($erreur);
    }
?>

<h1>Connexion</h1>

<form method="POST" action="index.php?p=connecter">

<div class="md3-field"><label for="email">Email</label><input type="email" id="email" name="email" required /></div>
<div class="md3-field"><label for="mdp">Mot de passe</label><input type="password" id="mdp" name="mdp" required minlength="<?= PASSWORD_MIN_LENGTH ?>"/></div>
<input type="submit" value="Se connecter" name="go" />
</form>
