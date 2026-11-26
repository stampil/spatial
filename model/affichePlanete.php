<?php
if(!empty($_GET['id_planete'])){
    $id_planete = (int) $_GET['id_planete'];
}
else{
    exit('id_planete needed');
}

$p = new Planete($id_planete);
if(!$p->systeme->estConnuDe($Joueur->id)){
    throw new UnexpectedValueException("Cette planète est inconnue.");
}
$couleur = $p->id_camp === null ? Camp::SANS_CAMP_COULEUR : (new Camp($p->id_camp))->couleur;
$map_planete=$id_planete%27;
if(!$map_planete) $map_planete = 1;
$defense = $p->bouclier + $p->canon;
$defenseurs = Combat::defenseurs($p);
?>
<?= Vue::filAriane(array(array(PAGES_TITRES['carte'], '?p=carte&systeme='.$p->id_systeme), array($p->systeme->nom, '?p=afficheSysteme&id_systeme='.$p->id_systeme), array($p->nom, null))) ?>
<section class="panneau" style="--accent:<?= htmlspecialchars($couleur) ?>">
    <div class="fiche__tete fiche__tete--hero">
        <div class="avatar avatar--planete avatar--planete-grand" style="background-image:url(img/planete/<?= $map_planete ?>.jpg)"></div>
        <div>
            <h1 class="fiche__nom"><?= htmlspecialchars($p->nom) ?></h1>
            <p class="md3-secondary">Système <?= $p->systeme->getNom() ?></p>
        </div>
    </div>
    <div class="pastilles">
        <?= Vue::pastille($p->slot.' '.__('emplacement', 'emplacements', $p->slot)) ?>
        <?php if($p->gouverneur === $Joueur->id){ echo Vue::pastille('Votre colonie', 'ok'); } ?>
        <?php if($p->detournement_fin !== null && $p->detournement_fin > Tools::now()){ echo Vue::pastille('Revenus détournés', 'alerte'); } ?>
    </div>
    <p>Gouverneur : <?= $p->getGouverneurNom() ?></p>
    <?php if($p->bonus !== null){ ?>
    <div class="ligne-jeu__info">
        <?= Vue::visuel('bonus', $p->bonus) ?>
        <p><strong>Bonus : <?= Bonus::libelle($p->bonus) ?>.</strong> <span class="md3-secondary"><?= htmlspecialchars(Bonus::description($p->bonus)) ?></span></p>
    </div>
    <?php } ?>
</section>

<section class="panneau" style="--accent:<?= htmlspecialchars($couleur) ?>">
    <h2 class="panneau__titre"><?= Icone::html('bouclier') ?>Défense</h2>
    <?= Vue::jauge('Bouclier', $p->bouclier, max(Planete::bouclierDeBase($p->slot), $p->bouclier), 'bouclier', 'bouclier') ?>
    <?= Vue::jauge('Canon', $p->canon, max(Planete::canonDeBase($p->slot), $p->canon), 'canon', 'canon') ?>
    <?= Vue::jauge('Garnison', $p->troupes, max(Planete::troupesDeBase($p->slot), $p->troupes), 'persos', 'troupes') ?>
    <?php if($p->gouverneur === 0 && $p->flotte_ia > 0){ ?>
    <p class="md3-secondary">Flotte stationnée :</p>
    <?= Vue::composition(array(Vaisseau::CHASSEUR => $p->flotte_ia), $p->id_camp) ?>
    <?php } ?>
    <?php if($p->gouverneur !== 0){ ?>
    <?= Vue::jauge('Révolte', $p->perc_revolte, Planete::MAX_REVOLTE, 'revolte', 'revolte') ?>
    <?php } ?>
    <?php if($p->defense_maj !== null && $p->id_camp !== null){ ?>
    <p class="md3-secondary">La défense se reconstitue : +<?= Planete::REGENERATION_POURCENT_PAR_HEURE ?> % par heure.</p>
    <?php } ?>
</section>
<?php
if($p->gouverneur !== $Joueur->id){
    $escadre = array_sum(Vaisseau::combattants($p->id, $Joueur->id, null));
    $troupes = Vaisseau::troupesEnBarge($p->id, $Joueur->id);
    $defenseActive = $defense > 0 || array_sum($defenseurs) > 0;
    $flottesPresentes = array();
    foreach(Flotte::liste($Joueur->id) as $ligne){
        $flotte = new Flotte($ligne->id);
        $engages = array_sum(Vaisseau::combattants($p->id, $Joueur->id, $flotte->id));
        if($flotte->planeteAuRepos() === $p->id && $defenseActive && $engages > 0){
            array_push($flottesPresentes, array('flotte' => $flotte, 'engages' => $engages));
        }
    }
    $attaque = $escadre > 0 && $defenseActive;
    $debarquement = $troupes > 0 && !$defenseActive;
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('canon') ?>Opérations</h2>
    <?php if(!$attaque && count($flottesPresentes) === 0 && !$debarquement){ ?>
    <p class="md3-secondary">Aucune opération possible : envoyez des vaisseaux sur cette planète depuis la carte.</p>
    <?php } ?>
    <div class="md3-actions">
    <?php if($attaque){ ?>
        <form method="POST" action="?p=combattre">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="action" value="attaquer" />
            <?= csrfField() ?>
            <input type="submit" value="Attaquer avec <?= $escadre ?> <?= __('vaisseau', 'vaisseaux', $escadre) ?> de combat" />
        </form>
    <?php } ?>
    <?php foreach($flottesPresentes as $presente){ ?>
        <form method="POST" action="?p=combattre">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="flotte" value="<?= $presente['flotte']->id ?>" />
            <input type="hidden" name="action" value="attaquer" />
            <?= csrfField() ?>
            <input type="submit" class="md3-button md3-button--tonal" value="Attaquer avec <?= htmlspecialchars($presente['flotte']->nom) ?> (<?= $presente['engages'] ?> <?= __('vaisseau', 'vaisseaux', $presente['engages']) ?>)" />
        </form>
    <?php } ?>
    <?php if($debarquement){ ?>
        <form method="POST" action="?p=combattre">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="action" value="debarquer" />
            <?= csrfField() ?>
            <input type="submit" value="Débarquer <?= $troupes ?> <?= __('troupe', 'troupes', $troupes) ?> des barges" />
        </form>
    <?php } ?>
    </div>
</section>
<?php
}
else{
    $planete = $p;
    require 'inc/planeteGestion.php';
}
