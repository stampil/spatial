<?php
$id_planet=1;
$planet = new Planet($id_planet);
for($id_planet=1; $id_planet<=26; $id_planet++){
    $bgz = 210;
    $w=100;
    if($id_planet%2){
        $bgz = 160;
        $w=75;
    }
    
?>

<div class="planet" title="<?=$planet->get_nom()?>" 
     style="background: url(img/planete/<?=$id_planet?>.jpg);background-size: <?=$bgz?>px;width: <?=$w?>px;height: <?=$w?>px;"></div>
<?php
}
?>
<?php
for($id_planet=1; $id_planet<=26; $id_planet++){
    $bgz = 210;
    $w=100;
    if($id_planet%2==0){
        $bgz = 310;
        $w=150;
    }
    
?>

<div class="planet" title="<?=$planet->get_nom()?>" 
     style="background: url(img/planete/<?=$id_planet?>.jpg);background-size: <?=$bgz?>px;width: <?=$w?>px;height: <?=$w?>px;"></div>
<?php
}
?>

