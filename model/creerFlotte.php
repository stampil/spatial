<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$nombres = is_array($_POST['nombre'] ?? null) ? $_POST['nombre'] : array();
$inclus = is_array($_POST['inclure'] ?? null) ? $_POST['inclure'] : array();
foreach(Vaisseau::typesDeFlotte() as $type){
    $nombres[$type] = isset($inclus[$type]) ? (int) giveInputString($nombres, $type) : 0;
}

$erreur = Flotte::creer($Joueur, $planete, giveInputString($_POST, 'nom'), $nombres);
Tools::addMessage($erreur ?? 'Flotte '.trim(giveInputString($_POST, 'nom')).' créée sur '.$planete->nom.'.');

?>

<script>
    location.href='?p=listeVaisseau';
</script>
