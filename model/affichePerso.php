<?php
if(!empty($_GET['id'])){
    $id = (int) $_GET['id'];
}
else{
    exit('id needed');
}

$o = new Perso($id);
$couleur = (new Camp($o->joueur->id_camp))->couleur;
$etat = Perso::ETATS[$o->etat()];
?>

<?php
if($Joueur->etape_tuto==1){
?>
<details class="mission" open>
    <summary><?= Icone::html('mission') ?>Mission : lancer la diplomatie</summary>
    <?php if($o->action_diplomatie === null){ ?>
    <p class="mission__texte"><strong>Lancez la diplomatie sur votre planète natale :</strong> touchez « Faire de la diplomatie » dans la section Actions, plus bas (<a href="#actions">aller aux actions</a>). Dans le tutoriel, elle réussit à coup sûr.</p>
    <?php } else { ?>
    <p class="mission__texte"><strong>La diplomatie est en cours :</strong> attendez la fin du compte à rebours dans la section Statut, la page se met à jour toute seule.</p>
    <?php } ?>
    <p class="mission__texte">Les caractéristiques de votre perso : il a <?= Perso::etoilesTexte($o->diplomatie) ?>/<?= Perso::MAX_STARS ?> <?= __('etoile', 'etoiles', intdiv($o->diplomatie + Perso::DEMIS_PAR_ETOILE - 1, Perso::DEMIS_PAR_ETOILE)) ?> en diplomatie. Un perso avec une faible diplomatie mettra plus de temps, voire pourra se faire emprisonner ou tuer plus facilement lors de ses missions. Dans ce tuto, nous n'irons pas dans ces extrêmes. Les actions de votre perso s'affichent de manière contextuelle, par exemple, si vous avez un vaisseau sur la même planète, une action "monter dans le vaisseau" apparaîtra.</p>
</details>
<?php   
}
?>

<section class="panneau" style="--accent:<?= htmlspecialchars($couleur) ?>">
    <div class="fiche__tete">
        <div class="avatar" style="--accent:<?= htmlspecialchars($couleur) ?>"><?= Vue::visuel('role', $o->role, Perso::ROLES[$o->role]['icone']) ?></div>
        <div>
            <h1 class="fiche__nom"><?= htmlspecialchars($o->nom) ?></h1>
            <div class="pastilles">
                <?= Vue::pastille($o->getRole()) ?>
                <?= Vue::pastille($etat['libelle'], $etat['variante']) ?>
            </div>
        </div>
    </div>
    <?= Vue::etoiles('Force', $o->FO, Perso::MAX_STARS, 'force') ?>
    <?= Vue::etoiles('Endurance', $o->PV, Perso::MAX_STARS, 'endurance') ?>
    <?= Vue::etoiles('Diplomatie', $o->diplomatie, Perso::MAX_STARS, 'diplomatie') ?>
</section>

<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('info') ?>Statut</h2>
<?php
if($o->fin_mort !== null){
?>
<p>Tué : réapparaît dans <span data-countdown="<?= htmlspecialchars($o->fin_mort) ?>"></span></p>
<?php
}
elseif($o->isImprisoned()){
?>
<p>Emprisonné : libéré dans <span data-countdown="<?= htmlspecialchars($o->fin_prison) ?>"></span></p>
<?php
}
elseif($o->arrivee !== null){
    $destination = new Planete($o->id_destination);
?>
<p>En voyage vers <?= $destination->systeme->estConnuDe($Joueur->id) ? $destination->getNom() : 'un secteur inconnu' ?> : arrivée dans <span data-countdown="<?= htmlspecialchars($o->arrivee) ?>"></span></p>
<?php
}
elseif($o->embarque){
?>
<p>Embarqué sur <?= $o->sur_planete->getNom() ?> : il partira avec le prochain transport personnel.</p>
<?php
}
elseif($o->action_diplomatie !== null){
?>
<p>Fin de la diplomatie : <span data-countdown="<?= htmlspecialchars($o->fin_diplomatie) ?>"></span></p>
<?php
}
else{
?>
<p>Libre<?= $o->sur_planete !== null ? ' sur '.$o->sur_planete->getNom() : '' ?></p>
<?php
}
?>
</section>

<section class="panneau" id="actions">
    <h2 class="panneau__titre"><?= Icone::html('actions') ?>Actions</h2>
<?php
if($o->canAct()){
    $planete = $o->sur_planete;
    if($o->role === Perso::ROLE_HEROS){
?>
Un héros n'agit pas seul : sa présence renforce vos combats sur cette planète (force) et vos diplomates qui y travaillent (diplomatie).
<?php
    }
    elseif($o->role === Perso::ROLE_ASSASSIN){
        $cibles = Perso::ennemis($Joueur, $planete);
        $lieu = new Planete($planete->id);
        $saboter = array();
        foreach(Batiment::CATALOGUE as $batiment => $infosBatiment){
            if($lieu->gouverneur !== $Joueur->id && $lieu->nombre($batiment) > 0){
                $saboter[$batiment] = $infosBatiment['nom'].' × '.$lieu->nombre($batiment);
            }
        }
        if(count($cibles) === 0 && $lieu->pnj_endurance === null && count($saboter) === 0){
?>
Aucune cible sur <?= $planete->getNom() ?>.
<?php
        }
        if($lieu->pnj_endurance !== null){
?>
<form class="action" method="POST" action="?p=assassinerGouverneur">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <?= csrfField() ?>
    <?= Vue::boutonIcone('assassin', 'Assassiner') ?>
    <span class="md3-secondary">Gouverneur de <?= htmlspecialchars($lieu->nom) ?> (<?= $lieu->pnj_endurance ?> <?= __('etoile', 'etoiles', $lieu->pnj_endurance) ?>) · réussite <?= $o->chanceAssassinatGouverneur($lieu) ?> %, orbite gardée : −<?= $lieu->penaliteOrbite() ?> %</span>
</form>
<?php
        }
        foreach($saboter as $batiment => $libelleBatiment){
?>
<form class="action" method="POST" action="?p=saboter">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="batiment" value="<?= $batiment ?>" />
    <?= csrfField() ?>
    <?= Vue::boutonIcone('saboter', 'Saboter') ?>
    <span class="md3-secondary"><?= htmlspecialchars($libelleBatiment) ?> · réussite <?= $o->chanceSabotage($lieu) ?> %</span>
</form>
<?php
        }
        foreach($cibles as $ligneCible){
            $cible = new Perso($ligneCible->id);
?>
<form class="action" method="POST" action="?p=assassiner">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="cible" value="<?= $cible->id ?>" />
    <?= csrfField() ?>
    <?= Vue::boutonIcone('assassin', 'Assassiner') ?>
    <span class="md3-secondary"><?= $cible->getNom() ?> de <?= htmlspecialchars($cible->joueur->nom) ?> (chance de réussite <?= $o->chanceAssassinat($cible) ?> %, en cas d'échec : prison ou mort)</span>
</form>
<?php
        }
    }
    elseif($planete->gouverneur === 0){
?>
<form class="action" method="POST" action="?p=faireDiplomatie">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="action" value="<?= Perso::ACTION_CONQUETE ?>" />
    <?= csrfField() ?>
    <?= Vue::boutonIcone('negocier', 'Faire de la diplomatie') ?>
    <span class="md3-secondary">sur <?= $planete->getNom() ?></span>
</form>
<?php
    }
    elseif($planete->gouverneur === $Joueur->id && $planete->perc_revolte > 0){
?>
<form class="action" method="POST" action="?p=faireDiplomatie">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="action" value="<?= Perso::ACTION_APAISER ?>" />
    <?= csrfField() ?>
    <?= Vue::boutonIcone('apaiser', 'Apaiser la révolte', false, 'md3-button--tonal') ?>
    <span class="md3-secondary">sur <?= $planete->getNom() ?></span>
</form>
<?php
    }
    else{
?>
Aucune action possible sur <?= $planete->getNom() ?>.
<?php
    }
    if(Perso::placesLibres($Joueur, $planete) > 0){
?>
<form class="action" method="POST" action="?p=embarquerPerso">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="action" value="embarquer" />
    <?= csrfField() ?>
    <?= Vue::boutonIcone('embarquer', 'Monter dans un transport', false, 'md3-button--tonal') ?>
    <span class="md3-secondary">sur <?= $planete->getNom() ?></span>
</form>
<?php
    }
}
elseif($o->embarque){
?>
<form method="POST" action="?p=embarquerPerso">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="action" value="descendre" />
    <?= csrfField() ?>
    <?= Vue::boutonIcone('decharger', 'Descendre du transport', false, 'md3-button--tonal') ?>
</form>
<?php
}
else{
?>
<p class="md3-secondary">Aucune action possible pour l'instant.</p>
<?php
}
?>
</section>
