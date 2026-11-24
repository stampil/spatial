<h1 class="titre-vue"><?= Icone::html('fusee') ?>Flottes</h1>
<?php
$posees = Vaisseau::posees($Joueur->id);
$enRoute = Vaisseau::enRoute($Joueur->id);
$flottes = Flotte::liste($Joueur->id);
$gouvernees = Planete::listeGouvernees($Joueur->id);

if(count($posees) === 0 && count($enRoute) === 0 && count($flottes) === 0){
?>
<p class="md3-secondary">Vous n'avez aucun vaisseau. Construisez une usine sur une planète que vous gouvernez pour en produire.</p>
<?php
}
?>
<p><a class="md3-button md3-button--tonal" href="?p=carte">Donner des ordres sur la carte</a></p>
<?php
foreach($flottes as $ligne){
    $flotte = new Flotte($ligne->id);
    $auRepos = $flotte->planeteAuRepos();
    $trajet = $flotte->enRoute();
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('persos') ?>Flotte <?= htmlspecialchars($flotte->nom) ?></h2>
    <?= Vue::composition($flotte->composition()) ?>
    <?php if($auRepos !== null){ $planete = new Planete($auRepos); ?>
    <p>Au repos sur <?= $planete->getNom() ?></p>
    <div class="md3-actions">
        <a class="md3-button md3-button--tonal" href="?p=carte&systeme=<?= $planete->id_systeme ?>">Voir sur la carte</a>
        <form method="POST" action="?p=gererFlotte">
            <input type="hidden" name="flotte" value="<?= $flotte->id ?>" />
            <input type="hidden" name="action" value="dissoudre" />
            <?= csrfField() ?>
            <input type="submit" class="md3-button md3-button--outlined" value="Dissoudre la flotte" />
        </form>
    </div>
    <?php } elseif($trajet !== null){ ?>
    <?php $destination = new Planete($trajet->id_destination); ?>
    <p class="md3-secondary">De <?= (new Planete($trajet->id_planete))->getNom() ?> vers <?= $destination->systeme->estConnuDe($Joueur->id) ? $destination->getNom() : 'un secteur inconnu' ?></p>
    <?php if($trajet->depart !== null){ ?>
    <?= Vue::progression('Trajet', strtotime($trajet->arrivee) - time(), strtotime($trajet->arrivee) - strtotime($trajet->depart), 'fusee') ?>
    <?php } else { ?>
    <p>Arrivée dans <span data-countdown="<?= htmlspecialchars($trajet->arrivee) ?>"></span></p>
    <?php } ?>
    <?php } ?>
</section>
<?php } ?>

<?php
foreach($posees as $planeteId => $types){
    $planete = new Planete($planeteId);
    $escadre = array_intersect_key($types, array_flip(Combat::ESCADRE));
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('ancre') ?><?= $planete->getNom() ?></h2>
    <?= Vue::composition($types) ?>
    <?php if(($types[Vaisseau::TRANSPORT_PERSO] ?? 0) > 0){ ?>
    <?php
    $embarques = array();
    foreach(Perso::embarques($Joueur, $planete) as $ligneEmbarque){
        array_push($embarques, (new Perso($ligneEmbarque->id))->getNom());
    }
    ?>
    <p class="md3-secondary">Persos embarqués (<?= count($embarques) ?>/<?= $types[Vaisseau::TRANSPORT_PERSO] * Vaisseau::CAPACITE_TRANSPORT_PERSO ?>) : <?= count($embarques) > 0 ? implode(', ', $embarques) : 'aucun' ?>. Ils partent avec le transport.</p>
    <?php } ?>
    <p><a href="?p=carte&systeme=<?= $planete->id_systeme ?>">Voir sur la carte</a></p>
    <?php if(count($escadre) > 0){ ?>
    <details class="systeme-details">
        <summary>Créer une flotte</summary>
        <form class="systeme-details__corps" method="POST" action="?p=creerFlotte">
            <input type="hidden" name="planete" value="<?= $planete->id ?>" />
            <?= csrfField() ?>
            <?php foreach($escadre as $type => $nombre){ ?>
            <div class="ligne-jeu">
                <span class="ligne-jeu__info"><?= Icone::html(Vaisseau::CATALOGUE[$type]['icone']) ?><?= Vaisseau::CATALOGUE[$type]['nom'] ?> × <?= $nombre ?></span>
                <input type="number" class="md3-number" name="nombre[<?= $type ?>]" min="0" max="<?= $nombre ?>" value="0" aria-label="Nombre dans la flotte" />
            </div>
            <?php } ?>
            <div class="ligne-jeu">
                <input type="text" class="md3-text" name="nom" maxlength="<?= Flotte::NOM_LONGUEUR_MAX ?>" placeholder="Nom de la flotte" required aria-label="Nom de la flotte" />
                <input type="submit" class="md3-button md3-button--tonal" value="Créer une flotte" />
            </div>
        </form>
    </details>
    <?php } ?>
</section>
<?php } ?>

<?php if(count($enRoute) > 0){ ?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('route') ?>En route</h2>
    <?php foreach($enRoute as $ligne){ $destination = new Planete($ligne->id_destination); ?>
    <div class="ligne-jeu">
        <div>
            <strong><?= Vaisseau::CATALOGUE[$ligne->type]['nom'] ?></strong> × <?= $ligne->nombre ?><br>
            <span class="md3-secondary">de <?= (new Planete($ligne->id_planete))->getNom() ?> vers <?= $destination->systeme->estConnuDe($Joueur->id) ? $destination->getNom() : 'un secteur inconnu' ?></span>
        </div>
        <?php if($ligne->depart !== null){ ?>
        <?= Vue::progression('Trajet', strtotime($ligne->arrivee) - time(), strtotime($ligne->arrivee) - strtotime($ligne->depart), Vaisseau::CATALOGUE[$ligne->type]['icone']) ?>
        <?php } else { ?>
        <span data-countdown="<?= htmlspecialchars($ligne->arrivee) ?>"></span>
        <?php } ?>
    </div>
    <?php } ?>
</section>
<?php } ?>

<?php
$productionsEnCours = array();
foreach($gouvernees as $ligne){
    foreach(Vaisseau::enProduction($ligne->id) as $production){
        array_push($productionsEnCours, $production);
    }
}
if(count($productionsEnCours) > 0){
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('usine_legere') ?>Production en cours</h2>
    <?php foreach($productionsEnCours as $production){ ?>
    <?= Vue::progression(Vaisseau::CATALOGUE[$production->type]['nom'].' · '.(new Planete($production->id_planete))->nom, strtotime($production->fin) - time(), $Joueur->dureeEffective(Vaisseau::CATALOGUE[$production->type]['duree']), Vaisseau::CATALOGUE[$production->type]['icone']) ?>
    <?php } ?>
</section>
<?php } ?>
