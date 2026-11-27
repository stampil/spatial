<?php
ob_clean();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars(APP_TITLE) ?></title>
    </head>
    <body>
        <script>
            try {
                localStorage.setItem(<?= json_encode(CONNEXION_CLE_STOCKAGE) ?>, <?= json_encode($jeton) ?>);
            } catch (erreur) {
            }
            location.replace('index.php?p=<?= HOME_PAGE ?>');
        </script>
    </body>
</html>
