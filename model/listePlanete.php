
<?php

$planetes = Planete::liste();
    
foreach($planetes as $p){
    $p = new Planete($p->id);
?>
<p> <?= $p->getNom() ?></p>

<?php } ?>




