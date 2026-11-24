
<?php

$joueurs = Joueur::liste();
    
foreach($joueurs as $j){
    $j = new Joueur($j->id);
?>
<p> <?= $j->getNom() ?></p>

<?php } ?>




