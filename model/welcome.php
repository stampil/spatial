<?php
if(!empty($_SESSION['Sid'])){
    header('Location: ?p='.HOME_PAGE);
    exit();
}
?>
<div class="accueil">
    <svg class="accueil__logo" width="96" height="96" viewBox="0 0 96 96" aria-hidden="true"><circle cx="48" cy="48" r="22" fill="currentColor"/><ellipse cx="48" cy="48" rx="40" ry="12" transform="rotate(-20 48 48)" fill="none" stroke="currentColor" stroke-width="4"/></svg>
    <h1><?= APP_TITLE ?></h1>
    <p class="accueil__devise">Explorez. Étendez. Exploitez. Exterminez.</p>
    <div class="md3-actions">
        <a class="md3-button" href="?p=inscription">Inscription</a>
        <a class="md3-button md3-button--tonal" href="?p=connection">Connexion</a>
    </div>
    <div class="accueil__camps">
        <?php foreach(Camp::liste() as $ligne){ ?>
        <div class="accueil__camp" style="--accent:<?= htmlspecialchars($ligne->couleur) ?>">
            <?= Vue::emblemeCamp((int) $ligne->id, 48) ?>
            <span><?= htmlspecialchars($ligne->nom) ?></span>
        </div>
        <?php } ?>
    </div>
</div>
