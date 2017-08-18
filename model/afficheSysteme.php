<?php
if(!empty($_GET['id_systeme'])){
    $id_systeme = $_GET['id_systeme'];
}
else{
    exit('id_systeme needed');
}

$s = new Systeme($id_systeme);

echo $s->nom?$s->nom:'Systeme'.$s->id;

?>
<p>possede les planetes suivantes</p>
<?php
foreach($s->planetes as $p){
    $map_planete=$p->id%27;
if(!$map_planete) $map_planete = 1;
    echo ($p->nom?$p->nom:'planete'.$p->id).' slot : '.$p->slot.'<div class="planet" style="background: url(img/planete/'.$map_planete.'.jpg);width:'.(50+3*$p->slot).'px;height:'.(50+3*$p->slot).'px"></div><br>';
}
?>


