<h1 class="titre-vue"><?= Icone::html('planetes') ?>Colonies</h1>
<?php
$gouvernees = Planete::listeGouvernees($Joueur->id);
if(count($gouvernees) === 0){
?>
<p class="md3-secondary">Vous ne gouvernez aucune planète pour l'instant.</p>
<?php
}
?>
<div class="fiches">
<?php
foreach($gouvernees as $ligne){
    $p = new Planete($ligne->id);
    $couleur = $p->id_camp === null ? Camp::SANS_CAMP_COULEUR : (new Camp($p->id_camp))->couleur;
    $map_planete = $p->id % 27;
    if(!$map_planete) $map_planete = 1;
    $file = Vaisseau::enFile($p->id);
    $stock = Vaisseau::stock($p->id, $Joueur->id);
?>
    <section class="panneau" style="--accent:<?= htmlspecialchars($couleur) ?>">
        <div class="fiche__tete">
            <div class="avatar avatar--planete" style="background-image:url(img/planete/<?= $map_planete ?>.jpg)"></div>
            <div>
                <h2 class="fiche__nom"><a href="?p=affichePlanete&id_planete=<?= $p->id ?>"><?= htmlspecialchars($p->nom) ?></a></h2>
                <p class="md3-secondary">Système <?= $p->systeme->getNom() ?></p>
            </div>
        </div>
        <div class="pastilles">
            <?= Vue::pastille(sprintf('%+d cr/h', $p->revenuNet())) ?>
            <?= Vue::pastille($p->batimentsTotal().' / '.$p->slot.' '.__('emplacement', 'emplacements', $p->slot)) ?>
            <?php if($p->bonus !== null){ echo Vue::pastille(Bonus::libelle($p->bonus), 'ok'); } ?>
            <?php if($p->perc_revolte >= Planete::REVOLTE_ALERTE){ echo Vue::pastille('Révolte élevée', 'alerte'); } ?>
        </div>
        <?= Vue::jauge('Révolte', $p->perc_revolte, Planete::MAX_REVOLTE, 'revolte', 'revolte') ?>
        <?php foreach($file as $ligneFile){ ?>
        <?= Vue::progression(Vaisseau::CATALOGUE[$ligneFile['type']]['nom'].' · '.$ligneFile['restants'].' '.__('restant', 'restants', $ligneFile['restants']), strtotime($ligneFile['fin']) - time(), $Joueur->dureeEffective(Vaisseau::CATALOGUE[$ligneFile['type']]['duree']), Vaisseau::CATALOGUE[$ligneFile['type']]['icone']) ?>
        <?php } ?>
        <?php if(count($stock) > 0){ echo Vue::composition($stock, $Joueur->id_camp); } ?>
    </section>
<?php } ?>
</div>

<h2>Planètes connues</h2>
<?php
$planetesParSysteme = array();
foreach(Planete::listeVisibles($Joueur->id) as $ligne){
    $planetesParSysteme[$ligne->id_systeme][] = $ligne;
}
foreach(Systeme::listeVisibles($Joueur->id) as $systeme){
?>
<details class="systeme-details">
    <summary><?= Vue::emblemeCamp($systeme->getIdCamp(), 24) ?><?= htmlspecialchars($systeme->nom) ?> <span class="md3-secondary">(<?= count($planetesParSysteme[$systeme->id] ?? array()) ?> planètes)</span></summary>
    <div class="systeme-details__corps">
        <?php foreach($planetesParSysteme[$systeme->id] ?? array() as $ligne){ $p = new Planete($ligne->id); ?>
        <div class="ligne-jeu">
            <span><?= $p->getNom() ?></span>
            <span class="md3-secondary"><?= $p->gouverneur === 0 ? 'Libre' : $p->getGouverneurNom() ?></span>
        </div>
        <?php } ?>
        <p><a href="?p=carte&systeme=<?= $systeme->id ?>">Voir sur la carte</a></p>
    </div>
</details>
<?php } ?>
