<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$depart = new Planete((int) ($_POST['depart'] ?? 0));
$exploration = giveInputString($_POST, 'destination') === Vaisseau::DESTINATION_EXPLORATION;
$destination = $exploration ? Vaisseau::cibleExploration($Joueur) : new Planete((int) ($_POST['destination'] ?? 0));
$nombres = is_array($_POST['nombre'] ?? null) ? $_POST['nombre'] : array();

if($destination === null){
    Tools::addMessage('Aucun secteur inconnu à explorer pour le moment.');
}
else{
    $troupes = (int) giveInputString($nombres, Vaisseau::TROUPE);
    $capacite = (int) giveInputString($nombres, Vaisseau::BARGE) * Vaisseau::CAPACITE_BARGE;
    $ordres = 0;
    foreach(Vaisseau::CATALOGUE as $type => $infos){
        $nombre = (int) giveInputString($nombres, $type);
        if($nombre < 1 || $troupes > $capacite){
            continue;
        }
        $ordres++;
        if($exploration && $type !== Vaisseau::RECONNAISSANCE){
            Tools::addMessage($infos['nom'].' : seul un vaisseau de reconnaissance explore un secteur inconnu.');
            continue;
        }
        $erreur = Vaisseau::deplacer($Joueur, $depart, $destination, $type, $nombre, $exploration);
        Tools::addMessage($erreur ?? $nombre.' × '.$infos['nom'].' : départ vers '.($exploration ? 'un secteur inconnu' : $destination->nom).', arrivée dans '.gmdate('i:s', Vaisseau::dureeTrajet($Joueur, $type, $depart, $destination)).'.');
    }
    if($troupes > $capacite){
        Tools::addMessage('Les troupes voyagent en barge : '.$troupes.' troupes demandent '.intdiv($troupes + Vaisseau::CAPACITE_BARGE - 1, Vaisseau::CAPACITE_BARGE).' barge(s), '.Vaisseau::CAPACITE_BARGE.' troupes par barge.');
    }
    elseif($ordres === 0){
        Tools::addMessage('Aucun vaisseau choisi.');
    }
}

?>

<script>
    location.href='?p=listeVaisseau';
</script>
