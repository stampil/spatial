
<?php

$joueurs = Joueur::liste();
    
foreach($joueurs as $j){
    $j = new Joueur($j->id);
?>
<p> <?= $j->getNom() ?> <?= Camp::libelle($j->id_camp) ?></p>

<?php } ?>




