
<?php

$planetes = Planete::liste();
    
foreach($planetes as $p){
    $p = new Planete($p->id);
?>
<p> <?= $p->getNom() ?> <?= Camp::libelle($p->id_camp) ?></p>

<?php } ?>




