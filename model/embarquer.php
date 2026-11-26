<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$type = giveInputString($_POST, 'type');
$erreur = Vaisseau::embarquer($Joueur, (int) ($_POST['vaisseau'] ?? 0), $type, (int) giveInputString($_POST, 'nombre'));
Tools::addMessage($erreur ?? 'Embarquement mis à jour.');

?>

<script>
    location.href='?p=listeVaisseau';
</script>
