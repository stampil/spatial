<?php
    $erreur = giveInputString($_GET, 'erreur');
    if($erreur !== ''){
        Tools::show_error_html($erreur);
    }
?>

<h1>Connection</h1>

<form method="POST" action="index.php?p=connecter">

<div>Email  : <input type="email" name="email" /></div>
<div>Mot de passe* : <input type="password" name="mdp" required minlength="6"/></div>
<input type="submit" value="submit" name="go" />
</form>
