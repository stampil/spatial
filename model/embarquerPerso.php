<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$perso = new Perso((int) ($_POST['perso'] ?? 0));
$action = giveInputString($_POST, 'action');

if($Joueur->id !== $perso->joueur->id){
    exit("Ce perso ne vous appartient pas");
}
if($action === 'embarquer'){
    $erreur = $perso->embarquer();
    Tools::addMessage($erreur ?? $perso->nom.' est monté dans un transport : il partira avec lui.');
}
elseif($action === 'descendre'){
    $erreur = $perso->descendre();
    Tools::addMessage($erreur ?? $perso->nom.' est descendu du transport.');
}
else{
    Tools::addMessage('Action inconnue.');
}

?>

<script>
    location.href='?p=affichePerso&id=<?= $perso->id ?>';
</script>
