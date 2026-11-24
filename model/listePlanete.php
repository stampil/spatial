
<?php

$planetes = Planete::listeVisibles($Joueur->id);
    
foreach($planetes as $p){
    $p = new Planete($p->id);
?>
<p> <?= $p->getNom() ?></p>

<?php } ?>




