<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$action = giveInputString($_POST, 'action');
if($action !== Menace::ACTION_COMBATTRE && $action !== Menace::ACTION_LAISSER){
    $erreur = 'Action inconnue.';
}
else{
    $erreur = Menace::repondre($Joueur, (int) ($_POST['menace'] ?? 0), $action === Menace::ACTION_COMBATTRE);
}
if($erreur !== null){
    Tools::addMessage($erreur);
}

?>

<script>
    location.href='?p=listeVaisseau';
</script>
