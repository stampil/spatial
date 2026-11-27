<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$action = giveInputString($_POST, 'action');

if($action === 'debarquer'){
    $erreur = Combat::debarquer($Joueur, $planete);
}
else{
    $erreur = 'Action inconnue.';
}
if($erreur !== null){
    Tools::addMessage($erreur);
}

?>

<script>
    location.href='?p=affichePlanete&id_planete=<?= $planete->id ?>';
</script>
