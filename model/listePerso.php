<?php
if($Joueur->etape_tuto==1){
?>
Voici la liste de tout les perso disponible.<br>
Pour l'instant il n'y a que vous, mais vous pourrez recruter par la suite des espions, diplomates et autres.<br>
cliquer sur votre perso, pour afficher ses caracteristiques et actions possible

<?php   
}
?>


<?php

$persos = Perso::liste($Joueur);
    
foreach($persos as $p){
    $perso = new Perso($p->id);
?>
<p> <a href="?p=affichePerso&id=<?php echo $perso->id ?>"><?php echo $perso->getNom() ?></a></p>

<?php } ?>




