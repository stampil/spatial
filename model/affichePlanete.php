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
?>
<section class="panneau" style="--accent:<?= htmlspecialchars($couleur) ?>">
    <div class="fiche__tete">
        <div class="avatar avatar--planete" style="background-image:url(img/planete/<?= $map_planete ?>.jpg)"></div>
        <div>
            <h1 class="fiche__nom"><?= htmlspecialchars($p->nom) ?></h1>
            <p class="md3-secondary">Système <?= $p->systeme->getNom() ?></p>
        </div>
    </div>
    <div class="pastilles">
        <?= Vue::pastille($p->slot.' emplacements') ?>
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
    <?php if($p->gouverneur !== 0){ ?>
    <?= Vue::jauge('Révolte', $p->perc_revolte, Planete::MAX_REVOLTE, 'revolte', 'revolte') ?>
    <?php } ?>
    <?php if($p->defense_maj !== null && $p->id_camp !== null){ ?>
    <p class="md3-secondary">La défense se reconstitue : +<?= Planete::REGENERATION_POURCENT_PAR_HEURE ?> % par heure.</p>
    <?php } ?>
</section>
<?php
if($p->gouverneur !== $Joueur->id){
    $presents = Vaisseau::stock($p->id, $Joueur->id);
    $escadre = 0;
    foreach(Combat::ESCADRE as $type){
        $escadre += $presents[$type] ?? 0;
    }
    $troupes = $presents[Vaisseau::TROUPE] ?? 0;
    $flottesPresentes = array();
    foreach(Flotte::liste($Joueur->id) as $ligne){
        $flotte = new Flotte($ligne->id);
        if($flotte->planeteAuRepos() === $p->id && $defense > 0){
            array_push($flottesPresentes, $flotte);
        }
    }
    $attaque = $escadre > 0 && $defense > 0;
    $debarquement = $troupes > 0 && $defense === 0;
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
            <input type="submit" value="Attaquer avec <?= $escadre ?> vaisseau(x) de combat" />
        </form>
    <?php } ?>
    <?php foreach($flottesPresentes as $flotte){ ?>
        <form method="POST" action="?p=combattre">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="flotte" value="<?= $flotte->id ?>" />
            <input type="hidden" name="action" value="attaquer" />
            <?= csrfField() ?>
            <input type="submit" class="md3-button md3-button--tonal" value="Attaquer avec la flotte <?= htmlspecialchars($flotte->nom) ?> (<?= array_sum($flotte->composition()) ?> vaisseau(x))" />
        </form>
    <?php } ?>
    <?php if($debarquement){ ?>
        <form method="POST" action="?p=combattre">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="action" value="debarquer" />
            <?= csrfField() ?>
            <input type="submit" value="Débarquer <?= $troupes ?> troupe(s) d'assaut" />
        </form>
    <?php } ?>
    </div>
</section>
<?php
    if($Joueur->id_camp === Camp::PIRATES){
        $escadrePirate = array_sum(Vaisseau::escadre($p->id, $Joueur->id));
        $adversaires = Defi::adversaires($Joueur, $p);
        $detournee = $p->detournement_fin !== null && $p->detournement_fin > Tools::now();
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('assassin') ?>Pirates</h2>
    <?php if($p->gouverneur === 0){ ?>
    <p class="md3-secondary">Cette planète ne produit aucune ressource à détourner.</p>
    <?php } elseif($detournee){ ?>
    <p class="md3-secondary">Revenus déjà détournés : prochain détournement possible à <?= htmlspecialchars($p->detournement_fin) ?>.</p>
    <?php } elseif($escadrePirate === 0){ ?>
    <p class="md3-secondary">Envoyez des vaisseaux de combat ici pour détourner les revenus.</p>
    <?php } elseif($defense > 0){ ?>
    <p class="md3-secondary">Détruisez d'abord la défense planétaire pour détourner les revenus.</p>
    <?php } else { ?>
    <form method="POST" action="?p=detourner">
        <input type="hidden" name="planete" value="<?= $p->id ?>" />
        <?= csrfField() ?>
        <input type="submit" value="Détourner les revenus (<?= Detournement::POURCENT ?> % de <?= Detournement::DUREE_HEURES ?> h)" />
    </form>
    <?php } ?>
    <?php foreach($adversaires as $adversaire){ ?>
    <div class="ligne-jeu">
        <span class="ligne-jeu__info"><?= Icone::html('canon') ?><?= $adversaire->getNom() ?></span>
        <?php if($escadrePirate > 0){ ?>
        <form method="POST" action="?p=defier">
            <input type="hidden" name="planete" value="<?= $p->id ?>" />
            <input type="hidden" name="defenseur" value="<?= $adversaire->id ?>" />
            <?= csrfField() ?>
            <input type="submit" class="md3-button md3-button--tonal" value="Défier" />
        </form>
        <?php } ?>
    </div>
    <?php } ?>
</section>
<?php
    }
}
else{
    $planete = $p;
    require 'inc/planeteGestion.php';
}
