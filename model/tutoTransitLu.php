<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
MyPDO::getInstance()->query("UPDATE " . MyPDO::DB_FLAG . "joueur SET tuto_transit_lu=1 WHERE id=?",$Joueur->id);

?>

<script>
    location.href='?p=<?= HOME_PAGE ?>';
</script>
