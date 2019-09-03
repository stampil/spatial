<?php


?>

Bienvenue  <?= $Joueur->nom; ?> sur ce jeu 4X  (exploration, expansion, exploitation et extermination).<br>
Dans cette phase de tuto, un nouveau systeme vient d'etre créé specialement pour vous. ce systeme sera invisible des autres joueurs pendant 7 jours.<br>
La premiere etape est donc de nommer votre systeme de départ :<br>
Attention  tout nom offensant peut entrainer un renommage arbitraire voir un bannissement du compte<br>
<form method="POST">
    Système de départ : <input type="text" name="mon_systeme" />
</form>
