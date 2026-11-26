<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$erreur = Vaisseau::renommer($Joueur, (int) ($_POST['vaisseau'] ?? 0), giveInputString($_POST, 'nom'));
Tools::addMessage($erreur ?? 'Vaisseau renommé.');

?>

<script>
    location.href='?p=listeVaisseau';
</script>
