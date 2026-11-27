<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "joueur SET tuto_explication_lue=etape_tuto WHERE id=?",$Joueur->id);

?>

<script>
    location.href='?p=<?= HOME_PAGE ?>';
</script>
