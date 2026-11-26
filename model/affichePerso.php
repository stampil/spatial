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
    <p class="mission__texte">Les caractéristiques de votre perso : il a <?= $o->diplomatie ?>/<?= Perso::MAX_STARS ?> étoile<?= $o->diplomatie > 1 ? 's' : '' ?> en diplomatie. Un perso avec une faible diplomatie mettra plus de temps, voire pourra se faire emprisonner ou tuer plus facilement lors de ses missions. Dans ce tuto, nous n'irons pas dans ces extrêmes. Les actions de votre perso s'affichent de manière contextuelle, par exemple, si vous avez un vaisseau sur la même planète, une action "monter dans le vaisseau" apparaîtra.</p>
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
    <?= Vue::jauge('Force', $o->FO, Perso::MAX_STARS, 'force') ?>
    <?= Vue::jauge('Endurance', $o->PV, Perso::MAX_STARS, 'endurance') ?>
    <?= Vue::jauge('Diplomatie', $o->diplomatie, Perso::MAX_STARS, 'diplomatie') ?>
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
        if(count($cibles) === 0){
?>
Aucune cible sur <?= $planete->getNom() ?>.
<?php
        }
        foreach($cibles as $ligneCible){
            $cible = new Perso($ligneCible->id);
?>
<form method="POST" action="?p=assassiner">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="cible" value="<?= $cible->id ?>" />
    <?= csrfField() ?>
    <input type="submit" value="Assassiner" /> <?= $cible->getNom() ?> de <?= htmlspecialchars($cible->joueur->nom) ?> (chance de réussite <?= $o->chanceAssassinat($cible) ?> %, en cas d'échec : prison ou mort)
</form>
<?php
        }
    }
    elseif($planete->gouverneur === 0){
?>
<form method="POST" action="?p=faireDiplomatie">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="action" value="<?= Perso::ACTION_CONQUETE ?>" />
    <?= csrfField() ?>
    <input type="submit" value="Faire de la diplomatie" /> sur <?= $planete->getNom() ?> (durée <?= gmdate('i:s', $o->dureeDiplomatie()) ?>, chance de réussite <?= $o->chanceDeSucces($Joueur) ?> %)
</form>
<?php
    }
    elseif($planete->gouverneur === $Joueur->id && $planete->perc_revolte > 0){
?>
<form method="POST" action="?p=faireDiplomatie">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="action" value="<?= Perso::ACTION_APAISER ?>" />
    <?= csrfField() ?>
    <input type="submit" class="md3-button md3-button--tonal" value="Apaiser la révolte" /> sur <?= $planete->getNom() ?> (durée <?= gmdate('i:s', $o->dureeDiplomatie()) ?>)
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
<form method="POST" action="?p=embarquerPerso">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="action" value="embarquer" />
    <?= csrfField() ?>
    <input type="submit" class="md3-button md3-button--tonal" value="Monter dans un transport" /> sur <?= $planete->getNom() ?>
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
    <input type="submit" class="md3-button md3-button--outlined" value="Descendre du transport" />
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
