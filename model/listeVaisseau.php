<?php
$posees = Vaisseau::posees($Joueur->id);
$enRoute = Vaisseau::enRoute($Joueur->id);

if(count($posees) === 0 && count($enRoute) === 0){
?>
<p class="md3-secondary">Vous n'avez aucun vaisseau. Construisez une usine sur une planète que vous gouvernez pour en produire.</p>
<?php
}

$planetesParSysteme = array();
foreach(Planete::listeVisibles($Joueur->id) as $ligne){
    $planetesParSysteme[$ligne->id_systeme][] = $ligne;
}
$options = '';
foreach(Systeme::listeVisibles($Joueur->id) as $systeme){
    $options .= '<optgroup label="'.htmlspecialchars($systeme->nom).'">';
    foreach($planetesParSysteme[$systeme->id] ?? array() as $planete){
        $options .= '<option value="'.(int) $planete->id.'">'.htmlspecialchars($planete->nom).'</option>';
    }
    $options .= '</optgroup>';
}
if($Joueur->id_systeme_voisin !== null && !Decouverte::existe($Joueur->id, $Joueur->id_systeme_voisin)){
    $options .= '<optgroup label="Secteur inexploré"><option value="'.(int) Planete::liste($Joueur->id_systeme_voisin)[0]->id.'">Système voisin (inconnu)</option></optgroup>';
}

foreach($posees as $planeteId => $types){
    $planete = new Planete($planeteId);
?>
<section class="md3-card">
    <h2><?= $planete->getNom() ?></h2>
    <form method="POST" action="?p=deplacer">
        <input type="hidden" name="depart" value="<?= $planete->id ?>" />
        <?= csrfField() ?>
        <?php foreach($types as $type => $nombre){ ?>
        <div class="md3-row">
            <div><strong><?= Vaisseau::CATALOGUE[$type]['nom'] ?></strong> × <?= $nombre ?></div>
            <input type="number" class="md3-number" name="nombre[<?= $type ?>]" min="0" max="<?= $nombre ?>" value="0" aria-label="Nombre à envoyer" />
        </div>
        <?php } ?>
        <div class="md3-row">
            <select class="md3-select" name="destination" required aria-label="Destination">
                <option value="" disabled selected>Destination</option>
                <?= $options ?>
            </select>
            <input type="submit" value="Envoyer" />
        </div>
    </form>
</section>
<?php } ?>

<?php if(count($enRoute) > 0){ ?>
<section class="md3-card">
    <h2>En route</h2>
    <?php foreach($enRoute as $ligne){ ?>
    <div class="md3-row">
        <div>
            <strong><?= Vaisseau::CATALOGUE[$ligne->type]['nom'] ?></strong> × <?= $ligne->nombre ?><br>
            <span class="md3-secondary">de <?= (new Planete($ligne->id_planete))->getNom() ?> vers <?= (new Planete($ligne->id_destination))->getNom() ?></span>
        </div>
        <span data-countdown="<?= htmlspecialchars($ligne->arrivee) ?>"></span>
    </div>
    <?php } ?>
</section>
<?php } ?>
