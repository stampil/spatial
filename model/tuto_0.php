<?php


?>

Bienvenue  <?= $Joueur->getNom(); ?> sur ce jeu 4X  (exploration, expansion, exploitation et extermination).<br>
Dans cette phase de tuto, un nouveau système vient d'être créé spécialement pour vous, le système <?= $Perso->sur_systeme->getNom(); ?>. Ce système deviendra visible des autres joueurs 7 jours après la fin du tuto.<br>
Votre planète natale : <?= $Perso->sur_planete->getNom(); ?> du système <?= $Perso->sur_systeme->getNom(); ?><br>
Humm... vous n'avez ni vaisseau, ni troupe, ni planète sous votre commandement...<br>
Pour construire des troupes et des vaisseaux, il vous faut une planète, il y a deux moyens de conquérir une planète, par la force ou par la diplomatie.<br />
Votre puissance de frappe étant réduite à vous et vous seul pour l'instant, optons pour la diplomatie.<br />
Cliquez sur « Liste des persos » pour voir votre (premier) personnage.<br>


<a href="?p=listePerso">Liste des persos</a>

