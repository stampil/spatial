<?php
$message = null;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
        exit('Jeton invalide');
    }
    $message = Installation::migrer();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@400;500&display=swap">
        <link rel="stylesheet" href="<?= Vue::ressource('css/main.css') ?>" type="text/css">
        <link rel="stylesheet" href="<?= Vue::ressource('css/md3.css') ?>" type="text/css">
        <link rel="stylesheet" href="<?= Vue::ressource('css/jeu.css') ?>" type="text/css">
        <title><?= htmlspecialchars(APP_TITLE.' · '.MIGRATION_LABEL) ?></title>
    </head>
    <body>
        <header class="md3-top-app-bar">
            <div class="md3-top-app-bar__lead">
                <span class="md3-top-app-bar__title"><?= APP_TITLE ?></span>
            </div>
            <nav class="md3-top-app-bar__nav" aria-label="Migration">
                <?= Vue::boutonMigration() ?>
            </nav>
        </header>
        <main class="md3-page">
            <h1 class="titre-vue"><?= Icone::html('info') ?><?= MIGRATION_LABEL ?></h1>
            <?php if($message === null){ ?>
            <p>La base du serveur est celle de l'ancienne version. Le bouton <?= MIGRATION_LABEL ?> importe <code><?= Installation::MIGRATION_FILE ?></code>, supprime les anciens comptes joueurs et recrée l'univers.</p>
            <?php } else { ?>
            <p role="status"><?= htmlspecialchars($message) ?></p>
            <p><a class="md3-button" href="?p=<?= GUEST_HOME_PAGE ?>">Ouvrir le jeu</a></p>
            <?php } ?>
        </main>
    </body>
</html>
