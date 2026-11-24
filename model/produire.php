<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$type = giveInputString($_POST, 'type');

$erreur = Vaisseau::produire($Joueur, $planete, $type);
Tools::addMessage($erreur ?? 'Production lancée : '.Vaisseau::CATALOGUE[$type]['nom'].' sur '.$planete->nom.'.');

?>

<script>
    location.href='?p=affichePlanete&id_planete=<?= $planete->id ?>';
</script>
