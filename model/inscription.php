<?php
    $erreur = giveInputString($_GET, 'erreur');
    if($erreur !== ''){
        Tools::show_error_html($erreur);
    }
?>

<h1 class="titre-vue"><?= Icone::html('inscription') ?>Inscription</h1>

<form method="POST" action="index.php?p=inscrire">


<fieldset class="factions">
<legend>Choisis ton camp *</legend>
<?php foreach(Camp::listeJouables() as $ligne){ $camp = new Camp($ligne->id); ?>
<label class="faction" style="--accent:<?= htmlspecialchars($camp->couleur) ?>">
<?= Vue::banniereCamp($camp->id) ?>
<input type="radio" name="camp" value="<?= $camp->id ?>" required />
<?= Vue::emblemeCamp($camp->id, 48) ?>
<span class="faction__nom"><?= htmlspecialchars($camp->nom) ?></span>
<span class="md3-secondary"><?= htmlspecialchars($camp->histoire) ?></span>
<span class="faction__avantage"><?= htmlspecialchars($camp->avantage()) ?></span>
</label>
<?php } ?>
</fieldset>
<div class="formulaire">
<div class="md3-field"><label for="nom">Nom du joueur *</label><input type="text" id="nom" name="nom" required /></div>
<div class="md3-field"><label for="mdp">Mot de passe *</label><input type="password" id="mdp" name="mdp" required/></div>
</div>
<input type="submit" class="md3-button md3-button--grand" value="S'inscrire" name="go" />
</form>
