<?php
if(!empty($_GET['id_planete'])){
    $id_planete = $_GET['id_planete'];
}
else{
    exit('id_planete needed');
}

$p = new Planete($id_planete);
//$p->perc_revolte++;
echo ($p->nom?$p->nom:'planète sans nom').' du systeme '.($p->systeme->nom?$p->systeme->nom:'sans nom').' revolté a '.$p->perc_revolte.' %  slot : '.$p->slot;

$p->save();   
/*$p->systeme->nom='SOL';
$p->systeme->save();*/

$map_planete=$id_planete%27;
if(!$map_planete) $map_planete = 1;
?>
<style>
  body {
  background:black;
  color:white;
    background-size:3024px;
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


