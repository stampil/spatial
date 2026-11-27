<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$erreur = $planete->renommer($Joueur, giveInputString($_POST, 'nom'));
Tools::addMessage($erreur ?? 'Planète renommée.');

?>

<script>
    location.href='?p=affichePlanete&id_planete=<?= $planete->id ?>';
</script>
