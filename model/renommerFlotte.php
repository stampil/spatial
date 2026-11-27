<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$erreur = Flotte::renommer($Joueur, (int) ($_POST['flotte'] ?? 0), giveInputString($_POST, 'nom'));
Tools::addMessage($erreur ?? 'Flotte renommée.');

?>

<script>
    location.href='?p=listeVaisseau';
</script>
