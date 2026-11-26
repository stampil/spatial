<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$flotte = new Flotte((int) ($_POST['flotte'] ?? 0));
$action = giveInputString($_POST, 'action');

if($flotte->id_joueur !== $Joueur->id){
    Tools::addMessage('Cette flotte ne vous appartient pas.');
}
elseif($action === 'dissoudre'){
    $flotte->dissoudre();
    Tools::addMessage($flotte->nom.' dissoute : ses vaisseaux redeviennent isolés.');
}
else{
    Tools::addMessage('Action inconnue.');
}

?>

<script>
    location.href='?p=listeVaisseau';
</script>
