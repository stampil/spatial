<?php
if($Joueur->etape_tuto==1){
?>
Voici la liste de tous les persos disponibles.<br>
Pour l'instant il n'y a que vous, mais vous pourrez recruter par la suite des espions, diplomates et autres.<br>
Cliquez sur votre perso pour afficher ses caractéristiques et actions possibles.

<?php   
}
?>


<?php

$persos = Perso::liste($Joueur);
    
foreach($persos as $p){
    $perso = new Perso($p->id);
?>
<p> <?= $perso->getNom() ?> (<?= $perso->getRole() ?>)</p>

<?php } ?>




