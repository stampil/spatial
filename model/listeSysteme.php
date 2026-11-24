
<?php

$systemes = Systeme::listeVisibles($Joueur->id);
    
foreach($systemes as $s){
    $s = new Systeme($s->id);
?>
<p> <?= $s->getNom() ?></p>

<?php } ?>




