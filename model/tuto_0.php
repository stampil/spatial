<?php


?>

Bienvenue  <?= $Joueur->getNom(); ?> sur ce jeu 4X  (exploration, expansion, exploitation et extermination).<br>
Dans cette phase de tuto, un nouveau systeme vient d'etre créé specialement pour vous, Le systeme <?= $Perso->sur_systeme->getNom(); ?>. ce systeme deviendra visible des autres joueurs 7 jours apres le tuto terminé.<br>
Votre planete natale : <?= $Perso->sur_planete->getNom(); ?> du systeme <?= $Perso->sur_systeme->getNom(); ?><br>
Humm... vous n'avez ni vaisseau, ni troupe, ni planete sous votre commandement...<br>
pour construire des troupes et des vaisseaux, il vous faut une planete, il y a deux moyen de conquerir une planete, par la force ou par la diplomatie.<br />
Votre puissance de frappe etant reduit a vous et vous seul pour l'instant, optons pour la diplomatie.<br />
Cliquer sur "Liste perso" pour voir Votre (premier) personnage.<br>


<a href="?p=listePerso" />Liste perso</a>

