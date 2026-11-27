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
<p><?= Vue::lienIcone('?p=carte&systeme='.$systeme->id, 'carte', 'Voir sur la carte', false, 'md3-button--tonal') ?></p>
<div class="fiches">
<?php
foreach($systeme->planetes as $p){
    $couleurPlanete = $p->id_camp === null ? Camp::SANS_CAMP_COULEUR : (new Camp($p->id_camp))->couleur;
    $releve = Analyse::releve($Joueur, $p);
    $stock = $posees[$p->id] ?? array();
    $flottes = $flottesAuRepos[$p->id] ?? array();
?>
    <section class="panneau fiche--lien" style="--accent:<?= htmlspecialchars($couleurPlanete) ?>">
        <div class="fiche__tete">
            <div class="avatar avatar--planete" style="background-image:url(<?= $p->cheminTexture() ?>)"></div>
            <div>
                <h2 class="fiche__nom"><a href="?p=affichePlanete&id_planete=<?= $p->id ?>" data-tuto="<?= Tuto::CLE_PLANETE.$p->id ?>"><?= htmlspecialchars($p->nom) ?></a></h2>
                <p class="md3-secondary"><?= Camp::libelle($p->id_camp) ?> · <?= $p->gouverneur === $Joueur->id ? 'Votre colonie' : ($p->gouverneur === 0 ? 'Libre' : $p->getGouverneurNom()) ?></p>
            </div>
        </div>
        <div class="pastilles">
            <?= Vue::pastille($p->slot.' '.__('emplacement', 'emplacements', $p->slot)) ?>
            <?= Vue::pastille('Bouclier '.($releve['bouclier'] ?? '?')) ?>
            <?= Vue::pastille('Canon '.($releve['canon'] ?? '?')) ?>
            <?= Vue::pastille('Garnison '.($releve['troupes'] ?? '?')) ?>
            <?php if($p->bonus !== null){ echo Vue::pastille(Bonus::libelle($p->bonus), 'ok'); } ?>
        </div>
        <?php if(count($stock) > 0 || count($flottes) > 0){ ?>
        <?php if(count($stock) > 0){ echo Vue::composition($stock, $Joueur->id_camp); } ?>
        <?php foreach($flottes as $flotte){ ?>
        <p class="md3-secondary"><?= htmlspecialchars($flotte->nom) ?> : <?= array_sum($flotte->composition()) ?> <?= __('vaisseau', 'vaisseaux', array_sum($flotte->composition())) ?></p>
        <?php } ?>
        <div class="md3-actions fiche__actions">
            <?= Vue::lienIcone('?p=listeVaisseau#planete-'.$p->id, 'voyager', 'Donner des ordres', false, 'md3-button--tonal') ?>
        </div>
        <?php } ?>
    </section>
<?php } ?>
</div>
