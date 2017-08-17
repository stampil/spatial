<?php
if(!empty($_GET['id_planete'])){
    $id_planete = $_GET['id_planete'];
}
else{
    exit('id_planete needed');
}

$p = new Planete($id_planete);
//$p->perc_revolte++;
echo $p->nom.' du systeme '.$p->systeme->nom.' revolté a '.$p->perc_revolte.' %';

$p->save();   
$p->systeme->nom='SOL';
$p->systeme->save();
?>


