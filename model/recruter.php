<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));

$erreur = $planete->recruter($Joueur);
Tools::addMessage($erreur ?? 'Un diplomate a été recruté sur '.$planete->nom.'.');

?>

<script>
    location.href='?p=affichePlanete&id_planete=<?= $planete->id ?>';
</script>
