# SUIVI

## À faire

### 1. Fin de tuto : conquérir les 3 planètes, en renommer une, des fermes, et les crédits pour tout cela (27/11)

Ta phrase : « en fin de tuto tu fait conquerire les 3 premiere planete qu'elle ne soit plus tenue par les IA, tu en fait renomer 1 et tu fait installer que des fermes pour que le joueur a la fin commencea voir les credit arrivé, arrange toi pour qu'on ai les sous necessaire a la construction de tout cela ».

Lu (rien codé) :
- Le tuto compte 17 étapes (la dernière est le Restrictor) puis s'arrête (`TUTO_END_STEP` = 18). Seule la planète 1 du système voisin est conquise (étape 8, assaut). La planète 2 est nettoyée (flotte, gouverneur, bouclier) et la planète 3 vient d'être vidée par le Restrictor, mais toutes deux restent au camp Pirates, sans gouverneur : personne ne les tient.
- Une planète sans défense se prend en débarquant des troupes d'assaut embarquées dans une barge (c'est la seule capture qui existe).
- Une ferme coûte 150 cr, rapporte 25 cr/h et coûte 3 cr/h de maintenance ; une planète rapporte 10 cr/h de base. Une conquête vide les bâtiments de la planète.
- Le renommage demande d'être gouverneur et d'avoir un perso sur la planète (l'assassin est resté sur la planète 2).
- Les crédits du tuto passent par des primes versées à l'entrée d'une étape (350, 400, 800, 1000 cr aujourd'hui).

Ce que je comprends : on ajoute des étapes à la fin du tuto, dans cet ordre :
1. Conquérir les planètes 2 et 3 par un débarquement (la planète 1 l'est déjà), pour qu'aucune des 3 premières planètes ne soit plus tenue par les Pirates.
2. En renommer une.
3. Installer uniquement des fermes sur les 3 planètes, pour que le joueur voie ses crédits monter à la minute quand il sort du tuto.
À l'entrée de chaque étape, une prime couvre exactement ce que l'étape demande de dépenser (troupes, fermes), avec une petite marge.

## Questions en suspens

1. Conquête : une étape « Prendre les deux planètes restantes » : le joueur produit les troupes nécessaires, les embarque dans la barge, envoie la flotte sur la planète 2 puis sur la planète 3 et débarque, comme à l'étape 8. Je recommande une seule étape pour les deux, avec la flèche guidant chaque débarquement.
2. Troupes : j'ajuste la garnison de la planète 2 et de la planète 3 dans le tuto pour qu'une barge de troupes suffise, et le débarquement du tuto est gagné d'avance (comme le combat du Restrictor). Je recommande cela.
3. Renommage : une étape « Nommer une planète » : le joueur renomme l'une des 3 planètes où il a un perso (l'assassin sur la planète 2 convient). Je recommande cela, objectif atteint dès qu'une des 3 est renommée.
4. Fermes : deux fermes par planète sur les 3 planètes (ou une seule s'il n'y a qu'un emplacement), soit 6 fermes au plus : 900 cr de construction, et environ +160 cr/h au total, soit près de +2,7 par minute. Une seule ferme par planète donnerait +96 cr/h (+1,6 par minute). Je recommande deux. L'objectif de l'étape : au moins une ferme sur chacune des 3 planètes.
5. Crédits : une prime à l'entrée de chaque étape, calculée sur les prix du jeu (troupes manquantes × prix, fermes × 150 cr, +20 % de marge), pas un chiffre fixe. Je recommande cela.
6. Numérotation : la fin du tuto passe de 18 à 21 (trois étapes ajoutées) ; une migration met les joueurs qui ont déjà fini le tuto à la nouvelle fin pour qu'ils ne soient pas renvoyés dedans. Je recommande cela.

À ne coder qu'à ton « go ».
