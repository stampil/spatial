<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$action = giveInputString($_POST, 'action');
if($action !== 'accepter' && $action !== 'refuser'){
    $erreur = 'Action inconnue.';
}
else{
    $erreur = Defi::repondre($Joueur, (int) ($_POST['defi'] ?? 0), $action === 'accepter');
}
if($erreur !== null){
    Tools::addMessage($erreur);
}

?>

<script>
    location.href='?p=listeVaisseau';
</script>
