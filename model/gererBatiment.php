<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$batiment = giveInputString($_POST, 'batiment');
$operation = giveInputString($_POST, 'operation');

if($operation === Planete::OPERATION_CONSTRUIRE){
    $erreur = $planete->construire($Joueur, $batiment);
    $verbe = 'construite';
}
elseif($operation === Planete::OPERATION_DEMOLIR){
    $erreur = $planete->demolir($Joueur, $batiment);
    $verbe = 'démolie';
}
else{
    $erreur = 'Opération inconnue.';
}
Tools::addMessage($erreur ?? Batiment::CATALOGUE[$batiment]['nom'].' '.$verbe.' sur '.$planete->nom.'.');

?>

<script>
    location.href='?p=affichePlanete&id_planete=<?= $planete->id ?>';
</script>
