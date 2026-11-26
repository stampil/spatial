# SUIVI

## À faire

### 1. Stats des persos en étoiles, plus de barre étirée sur PC (26/11)

Ta phrase : « les stats tu me les remet en etoiles, la la bare hiper allongé en pc c moche a crever ».

Lu (rien codé) : Force, Endurance et Diplomatie d'un perso (fiche `model/affichePerso.php` et liste `model/listePerso.php`) s'affichent en barres pleine largeur (`Vue::jauge`), donc très longues sur ordinateur. Avant les jauges (commit a75a00c), la diplomatie était écrite en texte « N/5 étoiles ».

Ce que je comprends : chaque stat devient une rangée de 5 étoiles (pleines pour la valeur, vides pour le reste), avec son icône et son nom, sans barre. Les autres jauges (bouclier, canon, garnison, révolte, planètes des camps) ne sont pas des stats d'étoiles : elles restent des barres.

## Questions en suspens

1. Étoiles dessinées en SVG (pleine / vide), même couleur lavande que le thème, avec le texte « 3/5 » pour les lecteurs d'écran. Je recommande cela.
2. Les autres barres (bouclier, canon, garnison, révolte, camps) sont aussi très étirées sur PC : je limite leur largeur à 360 px sur grand écran, inchangées sur téléphone. Je recommande oui.
