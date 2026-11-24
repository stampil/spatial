<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));

$role = giveInputString($_POST, 'role');

$erreur = $planete->recruter($Joueur, $role);
Tools::addMessage($erreur ?? Perso::ROLES[$role]['nom'].' recruté sur '.$planete->nom.'.');

?>

<script>
    location.href='?p=affichePlanete&id_planete=<?= $planete->id ?>';
</script>
