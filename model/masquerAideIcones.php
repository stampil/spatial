<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "joueur SET aide_icones_masquee=1 WHERE id=?",$Joueur->id);

?>

<script>
    location.href='?p=<?= HOME_PAGE ?>';
</script>
