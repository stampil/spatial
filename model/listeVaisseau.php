<?php
$posees = Vaisseau::posees($Joueur->id);
$enRoute = Vaisseau::enRoute($Joueur->id);
$flottes = Flotte::liste($Joueur->id);

if(count($posees) === 0 && count($enRoute) === 0 && count($flottes) === 0){
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
$optionExploration = '';
if(count(Systeme::explorables($Joueur->id)) > 0){
    $optionExploration = '<optgroup label="Exploration"><option value="'.Vaisseau::DESTINATION_EXPLORATION.'">Explorer un secteur inconnu (reconnaissance)</option></optgroup>';
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
                <?= $optionExploration ?>
            </select>
            <input type="submit" value="Envoyer" />
        </div>
    </form>
    <?php $escadre = array_intersect_key($types, array_flip(Combat::ESCADRE)); ?>
    <?php if(count($escadre) > 0){ ?>
    <form method="POST" action="?p=creerFlotte">
        <input type="hidden" name="planete" value="<?= $planete->id ?>" />
        <?= csrfField() ?>
        <?php foreach($escadre as $type => $nombre){ ?>
        <div class="md3-row">
            <div><strong><?= Vaisseau::CATALOGUE[$type]['nom'] ?></strong> × <?= $nombre ?></div>
            <input type="number" class="md3-number" name="nombre[<?= $type ?>]" min="0" max="<?= $nombre ?>" value="0" aria-label="Nombre dans la flotte" />
        </div>
        <?php } ?>
        <div class="md3-row">
            <input type="text" class="md3-text" name="nom" maxlength="<?= Flotte::NOM_LONGUEUR_MAX ?>" placeholder="Nom de la flotte" required aria-label="Nom de la flotte" />
            <input type="submit" class="md3-button md3-button--tonal" value="Créer une flotte" />
        </div>
    </form>
    <?php } ?>
</section>
<?php } ?>

<?php
foreach($flottes as $ligne){
    $flotte = new Flotte($ligne->id);
    $composition = array();
    foreach($flotte->composition() as $type => $nombre){
        array_push($composition, $nombre.' × '.Vaisseau::CATALOGUE[$type]['nom']);
    }
    $auRepos = $flotte->planeteAuRepos();
    $trajet = $flotte->enRoute();
?>
<section class="md3-card">
    <h2>Flotte <?= htmlspecialchars($flotte->nom) ?></h2>
    <p class="md3-secondary"><?= implode(', ', $composition) ?></p>
    <?php if($auRepos !== null){ ?>
    <p>Sur <?= (new Planete($auRepos))->getNom() ?></p>
    <form method="POST" action="?p=gererFlotte">
        <input type="hidden" name="flotte" value="<?= $flotte->id ?>" />
        <input type="hidden" name="action" value="envoyer" />
        <?= csrfField() ?>
        <div class="md3-row">
            <select class="md3-select" name="destination" required aria-label="Destination">
                <option value="" disabled selected>Destination</option>
                <?= $options ?>
            </select>
            <input type="submit" value="Envoyer la flotte" />
        </div>
    </form>
    <form method="POST" action="?p=gererFlotte">
        <input type="hidden" name="flotte" value="<?= $flotte->id ?>" />
        <input type="hidden" name="action" value="dissoudre" />
        <?= csrfField() ?>
        <input type="submit" class="md3-button md3-button--outlined" value="Dissoudre la flotte" />
    </form>
    <?php } elseif($trajet !== null){ ?>
    <?php $destination = new Planete($trajet->id_destination); ?>
    <div class="md3-row">
        <span class="md3-secondary">De <?= (new Planete($trajet->id_planete))->getNom() ?> vers <?= $destination->systeme->estConnuDe($Joueur->id) ? $destination->getNom() : 'un secteur inconnu' ?></span>
        <span data-countdown="<?= htmlspecialchars($trajet->arrivee) ?>"></span>
    </div>
    <?php } ?>
</section>
<?php } ?>

<?php if(count($enRoute) > 0){ ?>
<section class="md3-card">
    <h2>En route</h2>
    <?php foreach($enRoute as $ligne){ ?>
    <div class="md3-row">
        <div>
            <strong><?= Vaisseau::CATALOGUE[$ligne->type]['nom'] ?></strong> × <?= $ligne->nombre ?><br>
            <?php $destination = new Planete($ligne->id_destination); ?>
            <span class="md3-secondary">de <?= (new Planete($ligne->id_planete))->getNom() ?> vers <?= $destination->systeme->estConnuDe($Joueur->id) ? $destination->getNom() : 'un secteur inconnu' ?></span>
        </div>
        <span data-countdown="<?= htmlspecialchars($ligne->arrivee) ?>"></span>
    </div>
    <?php } ?>
</section>
<?php } ?>
