
<?php

$planetes = Planete::liste();
    
foreach($planetes as $p){
?>
<p> <a href="?p=affichePlanete&id_planete=<?php echo $p->id ?>"><?php echo $p->nom? $p->nom:'planète sans nom' ?></a></p>

<?php } ?>




