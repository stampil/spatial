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
                localStorage.removeItem(<?= json_encode(CONNEXION_CLE_STOCKAGE) ?>);
            } catch (erreur) {
            }
            location.replace('?p=connection');
        </script>
    </body>
</html>
