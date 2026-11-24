<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$depart = new Planete((int) ($_POST['depart'] ?? 0));
$destination = new Planete((int) ($_POST['destination'] ?? 0));
$nombres = is_array($_POST['nombre'] ?? null) ? $_POST['nombre'] : array();

$ordres = 0;
foreach(Vaisseau::CATALOGUE as $type => $infos){
    $nombre = (int) giveInputString($nombres, $type);
    if($nombre < 1){
        continue;
    }
    $ordres++;
    $erreur = Vaisseau::deplacer($Joueur, $depart, $destination, $type, $nombre);
    Tools::addMessage($erreur ?? $nombre.' × '.$infos['nom'].' : départ vers '.$destination->nom.', arrivée dans '.gmdate('i:s', Vaisseau::dureeTrajet($type, $depart, $destination)).'.');
}
if($ordres === 0){
    Tools::addMessage('Aucun vaisseau choisi.');
}

?>

<script>
    location.href='?p=listeVaisseau';
</script>
