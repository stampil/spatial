<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$action = giveInputString($_POST, 'action');

if($action === 'attaquer'){
    $flotteId = (int) ($_POST['flotte'] ?? 0);
    $erreur = Combat::attaquer($Joueur, $planete, $flotteId > 0 ? new Flotte($flotteId) : null);
}
elseif($action === 'debarquer'){
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
