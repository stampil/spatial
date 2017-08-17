
<?php

$joueurs = Joueur::liste();
    
foreach($joueurs as $j){
?>
<p> <a href="?p=afficheJoueur&id_joueur=<?php echo $j->id ?>"><?php echo $j->nom ?></a></p>

<?php } ?>




