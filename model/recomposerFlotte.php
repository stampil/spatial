<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$flotte = new Flotte((int) ($_POST['flotte'] ?? 0));
$vaisseaux = is_array($_POST['vaisseau'] ?? null) ? array_values(array_unique(array_map('intval', $_POST['vaisseau']))) : array();

if($flotte->id_joueur !== $Joueur->id){
    Tools::addMessage('Cette flotte ne vous appartient pas.');
}
elseif(count($vaisseaux) < Flotte::VAISSEAUX_MINIMUM){
    $flotte->dissoudre();
    Tools::addMessage($flotte->nom.' dissoute : moins de '.Flotte::VAISSEAUX_MINIMUM.' vaisseaux, ils redeviennent isolés.');
}
else{
    $erreur = $flotte->recomposer($vaisseaux);
    Tools::addMessage($erreur ?? $flotte->nom.' recomposée : '.count($vaisseaux).' vaisseaux.');
}

?>

<script>
    location.href='?p=listeVaisseau';
</script>
