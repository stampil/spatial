<?php
if(!empty($_GET['id'])){
    $id = $_GET['id'];
}
else{
    exit('id needed');
}

$o = new Perso($id);

?>

<?
if($Joueur->etape_tuto==1){
?>
Voici les caracteristiques de votre perso.<br>
Il a <?= $o->diplomatie ?>/5 ★  en diplomatie<br>
Un perso avec une faible diplomatie mettra plus de temps, voir pourra se faire emprisonner ou tuer plus facilement lors de ses missions.<br />
Dans ce tuto, nous n'iront pas dans ces extremes.<br/>
Les actions de votre perso s'affichent de maniere contextuels, par exemple, si vous avez un vaisseau sur la meme planete, une action "monter dans le vaisseau" apparaitra
<br />
<?   
}
?>

<fieldset><legend><?= $o->getNom() ?></legend>
Force : <?= $o->getFO() ?><br />
Endurance : <?= $o->getPV() ?><br />
Diplomatie:  <?= $o->getDiplomatie() ?><br />
</fieldset>
<br />
Action :<br />
Faire de la diplomatie sur




