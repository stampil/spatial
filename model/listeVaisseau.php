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
<?php
$menace = Menace::enCours($Joueur->id);
if($menace !== null){
    $cible = new Planete($menace->id_planete);
    $source = new Planete($menace->id_source);
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('canon') ?>Menace pirate</h2>
    <p>Une flotte pirate de <?= array_sum(Menace::flotte($source->slot)) ?> <?= __('vaisseau', 'vaisseaux', array_sum(Menace::flotte($source->slot))) ?>, venue de <?= $source->getNom() ?>, menace <?= $cible->getNom() ?>. Combattez-la avec vos vaisseaux présents sur la planète, ou laissez-la détourner une part des revenus. Sans réponse sous <?= intdiv(Menace::EXPIRATION_SECONDES, Planete::SECONDS_PER_HOUR) ?> h, elle détourne.</p>
    <?= Vue::composition(Menace::flotte($source->slot), Camp::PIRATES) ?>
    <div class="md3-actions">
        <?php foreach(array(Menace::ACTION_COMBATTRE => 'Combattre', Menace::ACTION_LAISSER => 'Laisser faire') as $action => $libelle){ ?>
        <form method="POST" action="?p=repondreMenace">
            <input type="hidden" name="menace" value="<?= $menace->id ?>" />
            <input type="hidden" name="action" value="<?= $action ?>" />
            <?= csrfField() ?>
            <input type="submit" class="md3-button<?= $action === Menace::ACTION_LAISSER ? ' md3-button--outlined' : '' ?>" value="<?= $libelle ?>" />
        </form>
        <?php } ?>
    </div>
</section>
<?php } ?>
<?php
foreach($flottes as $ligne){
    $flotte = new Flotte($ligne->id);
    $auRepos = $flotte->planeteAuRepos();
    $trajet = $flotte->enRoute();
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('persos') ?><?= htmlspecialchars($flotte->nom) ?></h2>
    <?= Vue::composition($flotte->composition(), $Joueur->id_camp) ?>
    <?php if($auRepos !== null){ $planete = new Planete($auRepos); $libres = Vaisseau::stockLibre($planete->id, $Joueur->id); ?>
    <p>Au repos sur <?= $planete->getNom() ?></p>
    <?php foreach($flotte->vaisseaux() as $vaisseau){ $avecVoyage = false; $avecCase = false; require 'inc/vaisseauNomme.php'; } ?>
    <details class="systeme-details">
        <summary>Renommer</summary>
        <form class="systeme-details__corps" method="POST" action="?p=renommerFlotte">
            <input type="hidden" name="flotte" value="<?= $flotte->id ?>" />
            <?= csrfField() ?>
            <div class="ligne-jeu">
                <input type="text" class="md3-text" name="nom" maxlength="<?= Flotte::NOM_LONGUEUR_MAX ?>" value="<?= htmlspecialchars($flotte->nom) ?>" required aria-label="Nom de la flotte" />
                <input type="submit" class="md3-button md3-button--tonal" value="Renommer" />
            </div>
        </form>
    </details>
    <div class="md3-actions">
        <a class="md3-button" href="?p=carte&ordre=<?= $planete->id ?>&flotte=<?= $flotte->id ?>">Voyager</a>
        <a class="md3-button md3-button--tonal" href="?p=carte&systeme=<?= $planete->id_systeme ?>">Voir sur la carte</a>
        <form method="POST" action="?p=gererFlotte">
            <input type="hidden" name="flotte" value="<?= $flotte->id ?>" />
            <input type="hidden" name="action" value="dissoudre" />
            <?= csrfField() ?>
            <input type="submit" class="md3-button md3-button--outlined" value="Dissoudre" />
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
$nommes = Vaisseau::nommesAuRepos($Joueur->id);
foreach(array_keys($posees) as $planeteId){
    $planete = new Planete($planeteId);
    $types = $posees[$planeteId];
    $libres = $types;
    $nommesPlanete = $nommes[$planete->id] ?? array();
    $creationPossible = count($nommesPlanete) >= Flotte::VAISSEAUX_MINIMUM;
?>
<section class="panneau" id="planete-<?= $planete->id ?>" data-minimum="<?= Flotte::VAISSEAUX_MINIMUM ?>">
    <div class="panneau__tete">
        <h2 class="panneau__titre"><?= Icone::html('ancre') ?><?= $planete->getNom() ?></h2>
        <?php if($creationPossible){ ?>
        <div class="panneau__outils">
            <button type="button" class="lien-action" data-flotte-creer>Créer une flotte</button>
            <button type="submit" class="lien-action" form="creation-flotte-<?= $planete->id ?>" data-flotte-enregistrer disabled>Créer une flotte</button>
            <button type="button" class="lien-action" data-flotte-annuler>Annuler</button>
        </div>
        <form id="creation-flotte-<?= $planete->id ?>" method="POST" action="?p=creerFlotte">
            <input type="hidden" name="planete" value="<?= $planete->id ?>" />
            <?= csrfField() ?>
        </form>
        <?php } ?>
    </div>
    <div class="hors-creation"><?= Vue::composition($types, $Joueur->id_camp) ?></div>
    <?php if(($types[Vaisseau::TRANSPORT_PERSO] ?? 0) > 0){ ?>
    <?php
    $embarques = array();
    foreach(Perso::embarques($Joueur, $planete) as $ligneEmbarque){
        array_push($embarques, (new Perso($ligneEmbarque->id))->getNom());
    }
    ?>
    <p class="md3-secondary hors-creation">Persos embarqués (<?= count($embarques) ?>/<?= $types[Vaisseau::TRANSPORT_PERSO] * Vaisseau::CAPACITE_TRANSPORT_PERSO ?>) : <?= count($embarques) > 0 ? implode(', ', $embarques) : 'aucun' ?>. Ils partent avec le transport.</p>
    <?php } ?>
    <p class="hors-creation"><a href="?p=carte&systeme=<?= $planete->id_systeme ?>">Voir sur la carte</a></p>
    <?php foreach($nommesPlanete as $vaisseau){ $avecVoyage = true; $avecCase = $creationPossible; require 'inc/vaisseauNomme.php'; } ?>
    <?php foreach($types as $type => $nombre){ if(Vaisseau::estNomme($type) || $type === Vaisseau::TROUPE){ continue; } ?>
    <div class="ligne-jeu hors-creation">
        <span class="ligne-jeu__info"><?= Icone::html(Vaisseau::CATALOGUE[$type]['icone']) ?><?= Vaisseau::CATALOGUE[$type]['nom'] ?> × <?= $nombre ?></span>
        <div class="md3-actions">
            <?php if($type === Vaisseau::RECONNAISSANCE){ ?>
            <form method="POST" action="?p=deplacer">
                <input type="hidden" name="depart" value="<?= $planete->id ?>" />
                <input type="hidden" name="destination" value="<?= Vaisseau::DESTINATION_EXPLORATION ?>" />
                <input type="hidden" name="nombre[<?= Vaisseau::RECONNAISSANCE ?>]" value="1" />
                <?= csrfField() ?>
                <input type="submit" class="md3-button md3-button--tonal" value="Chercher un système" />
            </form>
            <a class="md3-button" href="?p=carte&ordre=<?= $planete->id ?>&type=<?= $type ?>">Reconnaître une planète</a>
            <?php } else { ?>
            <a class="md3-button" href="?p=carte&ordre=<?= $planete->id ?>&type=<?= $type ?>">Voyager</a>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <?php if(($types[Vaisseau::TROUPE] ?? 0) > 0){ ?>
    <p class="md3-secondary hors-creation"><?= Icone::html(Vaisseau::CATALOGUE[Vaisseau::TROUPE]['icone']) ?><?= Vaisseau::CATALOGUE[Vaisseau::TROUPE]['nom'] ?> × <?= $types[Vaisseau::TROUPE] ?> : à embarquer sur un vaisseau pour voyager.</p>
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
            <span class="md3-secondary"><?= $ligne->exploration ? 'en expédition dans l\'inconnu depuis '.$destination->getNom().' (risque de ne pas revenir)' : 'de '.(new Planete($ligne->id_planete))->getNom().' vers '.($destination->systeme->estConnuDe($Joueur->id) ? $destination->getNom() : 'un secteur inconnu') ?></span>
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
    foreach(Vaisseau::enFile($ligne->id) as $production){
        $production['planete'] = $ligne->nom;
        array_push($productionsEnCours, $production);
    }
}
if(count($productionsEnCours) > 0){
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('usine_legere') ?>Production en cours</h2>
    <?php foreach($productionsEnCours as $production){ ?>
    <?= Vue::progression(Vaisseau::CATALOGUE[$production['type']]['nom'].' · '.$production['restants'].' '.__('restant', 'restants', $production['restants']).' · '.$production['planete'], strtotime($production['fin']) - time(), $Joueur->dureeEffective(Vaisseau::CATALOGUE[$production['type']]['duree']), Vaisseau::CATALOGUE[$production['type']]['icone']) ?>
    <?php } ?>
</section>
<?php } ?>
