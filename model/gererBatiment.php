<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$batiment = giveInputString($_POST, 'batiment');
$operation = giveInputString($_POST, 'operation');
$succes = '';

if($operation === Planete::OPERATION_CONSTRUIRE){
    $nombre = (int) giveInputString($_POST, 'nombre');
    $erreur = $planete->construire($Joueur, $batiment, $nombre);
    if($erreur === null){
        $succes = 'Construction lancée : '.$nombre.' × '.Batiment::CATALOGUE[$batiment]['nom'].' sur '.$planete->nom.'.';
    }
}
elseif($operation === Planete::OPERATION_DEMOLIR){
    $erreur = $planete->demolir($Joueur, $batiment);
    if($erreur === null){
        $succes = Batiment::CATALOGUE[$batiment]['nom'].' démolie sur '.$planete->nom.'.';
    }
}
else{
    $erreur = 'Opération inconnue.';
}
Tools::addMessage($erreur ?? $succes);

?>

<script>
    location.href='?p=affichePlanete&id_planete=<?= $planete->id ?>';
</script>
