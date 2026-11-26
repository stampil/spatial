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
    <p>Gouverneur : <?= $p->gouverneur === 0 ? 'voir la fiche de défense' : $p->getGouverneurNom() ?></p>
    <?php if($p->bonus !== null){ ?>
    <div class="ligne-jeu__info">
        <?= Vue::visuel('bonus', $p->bonus) ?>
        <p><strong>Bonus : <?= Bonus::libelle($p->bonus) ?>.</strong> <span class="md3-secondary"><?= htmlspecialchars(Bonus::description($p->bonus)) ?></span></p>
    </div>
    <?php } ?>
</section>

<?php
$proprietaire = $p->gouverneur === $Joueur->id;
$analyse = $proprietaire ? null : Analyse::derniere($Joueur->id, $p->id);
?>
<section class="panneau" style="--accent:<?= htmlspecialchars($couleur) ?>">
    <h2 class="panneau__titre"><?= Icone::html('bouclier') ?>Défense</h2>
    <?php if($proprietaire || $analyse !== null){ ?>
    <?php
    $bouclier = $proprietaire ? $p->bouclier : $analyse['bouclier'];
    $canon = $proprietaire ? $p->canon : $analyse['canon'];
    $troupes = $proprietaire ? $p->troupes : $analyse['troupes'];
    $flotteStationnee = $proprietaire ? array() : array_filter($analyse['flotte']);
    $boucliers = $proprietaire ? $p->nb_bouclier_planetaire : $analyse['boucliers'];
    $canons = $proprietaire ? $p->nb_canon_orbital : $analyse['canons'];
    ?>
    <?= Vue::jauge('Bouclier', $bouclier, max($p->bouclierMax(), $bouclier), 'bouclier', 'bouclier') ?>
    <?= Vue::jauge('Canon', $canon, max($p->canonMax(), $canon), 'canon', 'canon') ?>
    <?= Vue::jauge('Garnison', $troupes, max(Planete::troupesDeBase($p->slot), $troupes), 'persos', 'troupes') ?>
    <p class="md3-secondary"><?= $boucliers ?> <?= __('bouclier_planetaire', 'boucliers_planetaires', $boucliers) ?> · <?= $canons ?> <?= __('canon_orbital', 'canons_orbitaux', $canons) ?></p>
    <?php if(count($flotteStationnee) > 0){ ?>
    <p class="md3-secondary">Flotte en orbite :</p>
    <?= Vue::composition($flotteStationnee, $p->id_camp) ?>
    <?php } ?>
    <?php if(!$proprietaire){ ?>
    <p class="md3-secondary"><?= $analyse['batiments'] ?> <?= __('batiment', 'batiments', $analyse['batiments']) ?> · gouverneur : <?= $analyse['gouverneur'] === null ? 'aucun' : 'en place ('.$analyse['gouverneur'].' '.__('etoile', 'etoiles', $analyse['gouverneur']).')' ?> · analysée le <?= htmlspecialchars($analyse['le']) ?></p>
    <?php } ?>
    <?php } else { ?>
    <p class="md3-secondary">Bouclier, canon, garnison, flotte en orbite, bâtiments et gouverneur : inconnus. Envoyez-y un vaisseau de reconnaissance pour relever la fiche complète.</p>
    <?php } ?>
    <?php if($p->gouverneur !== 0){ ?>
    <?= Vue::jauge('Révolte', $p->perc_revolte, Planete::MAX_REVOLTE, 'revolte', 'revolte') ?>
    <?php } ?>
    <?php if($p->defense_maj !== null){ ?>
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
        <form class="action" method="POST" action="?p=combattre">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="action" value="attaquer" />
            <?= csrfField() ?>
            <?= Vue::boutonIcone('attaque', 'Attaquer') ?>
            <span class="md3-secondary"><?= $escadre ?> <?= __('vaisseau', 'vaisseaux', $escadre) ?></span>
        </form>
    <?php } ?>
    <?php foreach($flottesPresentes as $presente){ ?>
        <form class="action" method="POST" action="?p=combattre">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="flotte" value="<?= $presente['flotte']->id ?>" />
            <input type="hidden" name="action" value="attaquer" />
            <?= csrfField() ?>
            <?= Vue::boutonIcone('attaque_flotte', 'Attaquer avec la flotte', false, 'md3-button--tonal', ' data-tuto="attaque_flotte"') ?>
            <span class="md3-secondary"><?= htmlspecialchars($presente['flotte']->nom) ?> · <?= $presente['engages'] ?> <?= __('vaisseau', 'vaisseaux', $presente['engages']) ?></span>
        </form>
    <?php } ?>
    <?php if($debarquement){ ?>
        <form class="action" method="POST" action="?p=combattre">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="action" value="debarquer" />
            <?= csrfField() ?>
            <?= Vue::boutonIcone('debarquement', 'Débarquer', false, '', ' data-tuto="debarquement"') ?>
            <span class="md3-secondary"><?= $troupes ?> <?= __('troupe', 'troupes', $troupes) ?></span>
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
