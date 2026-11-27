<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$depart = new Planete((int) ($_POST['depart'] ?? 0));
$nombres = is_array($_POST['nombre'] ?? null) ? $_POST['nombre'] : array();

if(giveInputString($_POST, 'destination') === Vaisseau::DESTINATION_EXPLORATION){
    $nombre = (int) giveInputString($nombres, Vaisseau::RECONNAISSANCE);
    $erreur = Vaisseau::explorer($Joueur, $depart, $nombre);
    Tools::addMessage($erreur ?? $nombre.' × '.Vaisseau::CATALOGUE[Vaisseau::RECONNAISSANCE]['nom'].' : départ vers l\'inconnu, retour dans '.gmdate('i:s', Vaisseau::dureeExpedition($Joueur)).' (risque de ne pas revenir : '.Vaisseau::EXPLORATION_PERTE_POURCENT.' %).');
}
else{
    $choix = array();
    foreach(array_keys(Vaisseau::CATALOGUE) as $type){
        $choix[$type] = (int) giveInputString($nombres, $type);
    }
    $flottes = array();
    foreach(is_array($_POST['flotte'] ?? null) ? $_POST['flotte'] : array() as $flotteId){
        array_push($flottes, new Flotte((int) $flotteId));
    }
    $vaisseaux = array();
    if(!empty($_POST['vaisseau'])){
        array_push($vaisseaux, (int) $_POST['vaisseau']);
    }
    $erreur = Vaisseau::ordonner($Joueur, $depart, new Planete((int) ($_POST['destination'] ?? 0)), $choix, $flottes, $vaisseaux);
    if($erreur !== null){
        Tools::addMessage($erreur);
    }
}

?>

<script>
    location.href='?p=listeVaisseau';
</script>
