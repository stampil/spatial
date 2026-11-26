<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
Tuto::valider($Joueur);
?>

<script>
    location.href='?p=<?= HOME_PAGE ?>';
</script>
