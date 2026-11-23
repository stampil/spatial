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
if(!Tools::isFinCompteRebours($o->fin_diplomatie)){
    ?>
Fin de la diplomatie :
<span data-countdown="<?= htmlspecialchars($o->fin_diplomatie) ?>"></span>
<br>
<?php } ?>
<br />
Action :<br />
<?php
if(Tools::isFinCompteRebours($o->fin_diplomatie)){
?>
<form method="POST" action="?p=faireDiplomatie">
    <input type="hidden" name="perso" value="<?= $o->id ?>" />
    <input type="hidden" name="<?= CSRF_KEY ?>" value="<?= $_SESSION[CSRF_KEY] ?>" />
    <input type="submit" value="Faire de la diplomatie" /> sur <?= $o->sur_planete->getNom() ?>
</form>
<?php
}
?>



