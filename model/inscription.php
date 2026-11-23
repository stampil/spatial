<?php
    $erreur = giveInputString($_GET, 'erreur');
    if($erreur !== ''){
        Tools::show_error_html($erreur);
    }
?>

<h1>Inscription</h1>

<form method="POST" action="index.php?p=inscrire">


<div>Email * : <input type="email" name="email" required /></div>
<div>Nom du joueur* : <input type="text" name="nom" required /></div>
<div>Mot de passe* : <input type="password" name="mdp" required minlength="<?= PASSWORD_MIN_LENGTH ?>"/></div>
<input type="submit" value="submit" name="go" />
</form>
