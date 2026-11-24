<?php
if(!empty($_GET['id'])){
    $id = (int) $_GET['id'];
}
else{
    exit('id needed');
}

$o = new Perso($id);

?>

<?php
if($Joueur->etape_tuto==1){
?>
Voici les caractéristiques de votre perso.<br>
Il a <?= $o->diplomatie ?>/<?= Perso::MAX_STARS ?> étoile<?= $o->diplomatie > 1 ? 's' : '' ?> en diplomatie<br>
Un perso avec une faible diplomatie mettra plus de temps, voire pourra se faire emprisonner ou tuer plus facilement lors de ses missions.<br />
Dans ce tuto, nous n'irons pas dans ces extrêmes.<br/>
Les actions de votre perso s'affichent de manière contextuelle, par exemple, si vous avez un vaisseau sur la même planète, une action "monter dans le vaisseau" apparaîtra
<br />
<?php   
}
?>

<?= $o->getNom() ?><br>
Force : <?= $o->getFO() ?><br />
Endurance : <?= $o->getPV() ?><br />
Diplomatie : <?= $o->getDiplomatie() ?><br />

<br />
Statut :<br />
<?php
if($o->fin_mort !== null){
?>
Tué : réapparaît dans <span data-countdown="<?= htmlspecialchars($o->fin_mort) ?>"></span><br>
<?php
}
elseif($o->isImprisoned()){
?>
Emprisonné : libéré dans <span data-countdown="<?= htmlspecialchars($o->fin_prison) ?>"></span><br>
<?php
}
elseif($o->arrivee !== null){
    $destination = new Planete($o->id_destination);
?>
En voyage vers <?= $destination->systeme->estConnuDe($Joueur->id) ? $destination->getNom() : 'un secteur inconnu' ?> : arrivée dans <span data-countdown="<?= htmlspecialchars($o->arrivee) ?>"></span><br>
<?php
}
elseif($o->embarque){
?>
Embarqué sur <?= $o->sur_planete->getNom() ?> : il partira avec le prochain transport personnel.<br>
<?php
}
elseif($o->action_diplomatie !== null){
?>
Fin de la diplomatie : <span data-countdown="<?= htmlspecialchars($o->fin_diplomatie) ?>"></span><br>
<?php
}
else{
?>
Libre<br>
<?php
}
?>
<br />
Action :<br />
<?php
if($o->canAct()){
    $planete = $o->sur_planete;
    if($planete->gouverneur === 0){
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
?>
