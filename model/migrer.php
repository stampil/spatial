<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$message = Installation::migrer();
unset($_SESSION['Sid']);
Tools::addMessage($message);
?>

<script>
    location.href='?p=<?= GUEST_HOME_PAGE ?>';
</script>
