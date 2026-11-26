<?php
if(empty($_GET['id_systeme'])){
    exit('id_systeme needed');
}
$systeme = new Systeme((int) $_GET['id_systeme']);
if(!$systeme->estConnuDe($Joueur->id)){
    throw new UnexpectedValueException("Ce système est inconnu.");
}
$idCamp = $systeme->getIdCamp();
$couleur = $idCamp === null ? Camp::SANS_CAMP_COULEUR : (new Camp($idCamp))->couleur;
$nomCamp = $idCamp === null ? Camp::SANS_CAMP_NOM : (new Camp($idCamp))->nom;
$posees = Vaisseau::posees($Joueur->id);
$flottesAuRepos = array();
foreach(Flotte::liste($Joueur->id) as $ligne){
    $flotte = new Flotte($ligne->id);
    $auRepos = $flotte->planeteAuRepos();
    if($auRepos !== null){
        $flottesAuRepos[$auRepos][] = $flotte;
    }
}
?>
<?= Vue::filAriane(array(array(PAGES_TITRES['carte'], '?p=carte&systeme='.$systeme->id), array($systeme->nom, null))) ?>
<h1 class="titre-vue" style="--accent:<?= htmlspecialchars($couleur) ?>"><?= Vue::emblemeCamp($idCamp, 32) ?><?= htmlspecialchars($systeme->nom) ?></h1>
<div class="pastilles">
    <?= Vue::pastille($nomCamp) ?>
    <?= Vue::pastille(count($systeme->planetes).' '.__('planete', 'planetes', count($systeme->planetes))) ?>
</div>
<p><a class="md3-button md3-button--tonal" href="?p=carte&systeme=<?= $systeme->id ?>">Voir sur la carte</a></p>
<div class="fiches">
<?php
foreach($systeme->planetes as $p){
    $couleurPlanete = $p->id_camp === null ? Camp::SANS_CAMP_COULEUR : (new Camp($p->id_camp))->couleur;
    $map_planete = $p->id % 27;
    if(!$map_planete) $map_planete = 1;
    $stock = $posees[$p->id] ?? array();
    $flottes = $flottesAuRepos[$p->id] ?? array();
?>
    <section class="panneau fiche--lien" style="--accent:<?= htmlspecialchars($couleurPlanete) ?>">
        <div class="fiche__tete">
            <div class="avatar avatar--planete" style="background-image:url(img/planete/<?= $map_planete ?>.jpg)"></div>
            <div>
                <h2 class="fiche__nom"><a href="?p=affichePlanete&id_planete=<?= $p->id ?>"><?= htmlspecialchars($p->nom) ?></a></h2>
                <p class="md3-secondary"><?= Camp::libelle($p->id_camp) ?> · <?= $p->gouverneur === $Joueur->id ? 'Votre colonie' : ($p->gouverneur === 0 ? 'Libre' : $p->getGouverneurNom()) ?></p>
            </div>
        </div>
        <div class="pastilles">
            <?= Vue::pastille($p->slot.' '.__('emplacement', 'emplacements', $p->slot)) ?>
            <?= Vue::pastille('Bouclier '.$p->bouclier) ?>
            <?= Vue::pastille('Canon '.$p->canon) ?>
            <?= Vue::pastille('Garnison '.$p->troupes) ?>
            <?php if($p->bonus !== null){ echo Vue::pastille(Bonus::libelle($p->bonus), 'ok'); } ?>
        </div>
        <?php if(count($stock) > 0 || count($flottes) > 0){ ?>
        <?php if(count($stock) > 0){ echo Vue::composition($stock, $Joueur->id_camp); } ?>
        <?php foreach($flottes as $flotte){ ?>
        <p class="md3-secondary"><?= htmlspecialchars($flotte->nom) ?> : <?= array_sum($flotte->composition()) ?> <?= __('vaisseau', 'vaisseaux', array_sum($flotte->composition())) ?></p>
        <?php } ?>
        <div class="md3-actions fiche__actions">
            <a class="md3-button md3-button--tonal" href="?p=listeVaisseau#planete-<?= $p->id ?>">Donner des ordres</a>
        </div>
        <?php } ?>
    </section>
<?php } ?>
</div>
