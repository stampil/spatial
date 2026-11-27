<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
Tools::addMessage(Installation::reinitialiser());
?>

<script>
    location.href='?p=<?= HOME_PAGE ?>';
</script>
