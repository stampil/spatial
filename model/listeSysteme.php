
<?php

$systemes = Systeme::liste();
    
foreach($systemes as $s){
    $s = new Systeme($s->id);
?>
<p> <?= $s->getNom() ?> <?= Camp::libelle($s->getIdCamp()) ?></p>

<?php } ?>




