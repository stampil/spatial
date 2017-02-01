<?php
if(!empty($_GET['id_planete'])){
    $id_planete = $_GET['id_planete'];
}
else{
    exit('id_planete needed');
}

$p = new Planete();
$p->load($id_planete);
echo $p->nom.' revolté a '.$p->perc_revolte.' %';

    
?>


