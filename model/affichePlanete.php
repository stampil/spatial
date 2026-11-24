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
//$p->perc_revolte++;
echo '<p>'.($p->nom?$p->getNom():'planète sans nom').' du système '.($p->systeme->nom?$p->systeme->getNom():'sans nom').' révoltée à '.$p->perc_revolte.' %  slot : '.$p->slot.'</p>';
echo '<p>Gouverneur : '.$p->getGouverneurNom().'</p>';
echo '<p>Défense : bouclier '.$p->bouclier.', canon '.$p->canon.', garnison '.$p->troupes.' troupe(s).</p>';
if($p->bonus !== null){
    echo '<p>Bonus : '.Bonus::libelle($p->bonus).'. '.Bonus::description($p->bonus).'</p>';
}

/*$p->systeme->nom='SOL';
$p->systeme->save();*/

$map_planete=$id_planete%27;
if(!$map_planete) $map_planete = 1;
?>
<style>
  body {
  /*background:black;
  color:white;
    background-size:3024px;*/
}

#planet {
        width: 100px;
	height: 100px;
	background: url(img/planete/<?php echo $map_planete; ?>.jpg);
	border-radius: 50%;
	background-size: 210px;
	box-shadow: inset 16px 0 40px 6px rgb(0, 0, 0),
		inset -3px 0 6px 2px rgba(255, 255, 255, 0.2);
	animation-name: rotate;
	animation-duration: 180s;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
}

@keyframes rotate {
  from { background-position-x: 0px; }
  to { background-position-x: 1440px; }
}
</style>
<div id="planet"></div>
<?php
if($p->gouverneur !== $Joueur->id){
    $presents = Vaisseau::stock($p->id, $Joueur->id);
    $escadre = 0;
    foreach(Combat::ESCADRE as $type){
        $escadre += $presents[$type] ?? 0;
    }
    $troupes = $presents[Vaisseau::TROUPE] ?? 0;
    if($escadre > 0 && $p->bouclier + $p->canon > 0){
?>
<form method="POST" action="?p=combattre">
    <input type="hidden" name="planete" value="<?= $p->id ?>" />
    <input type="hidden" name="action" value="attaquer" />
    <?= csrfField() ?>
    <input type="submit" value="Attaquer avec <?= $escadre ?> vaisseau(x) de combat" />
</form>
<?php
    }
    foreach(Flotte::liste($Joueur->id) as $ligne){
        $flotte = new Flotte($ligne->id);
        if($flotte->planeteAuRepos() !== $p->id || $p->bouclier + $p->canon === 0){
            continue;
        }
?>
<form method="POST" action="?p=combattre">
    <input type="hidden" name="planete" value="<?= $p->id ?>" />
    <input type="hidden" name="flotte" value="<?= $flotte->id ?>" />
    <input type="hidden" name="action" value="attaquer" />
    <?= csrfField() ?>
    <input type="submit" value="Attaquer avec la flotte <?= htmlspecialchars($flotte->nom) ?> (<?= array_sum($flotte->composition()) ?> vaisseau(x))" />
</form>
<?php
    }
    if($troupes > 0 && $p->bouclier + $p->canon === 0){
?>
<form method="POST" action="?p=combattre">
    <input type="hidden" name="planete" value="<?= $p->id ?>" />
    <input type="hidden" name="action" value="debarquer" />
    <?= csrfField() ?>
    <input type="submit" value="Débarquer <?= $troupes ?> troupe(s) d'assaut" />
</form>
<?php
    }
}
if($p->gouverneur === $Joueur->id){
    $planete = $p;
    require 'inc/planeteGestion.php';
}
?>


