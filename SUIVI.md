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

Décidé le 27/11 (« tout recommandé sauf 4 : non sa doit etre full fermes ») : conquête en une étape avec flèches à chaque débarquement ; garnisons ajustées et débarquement du tuto gagné d'avance ; renommage d'une des 3 planètes où le joueur a un perso, validé dès qu'une est renommée ; primes calculées sur les prix du jeu avec 20 % de marge ; fin du tuto de 18 à 21 avec migration des joueurs qui ont fini. Fermes : full fermes, tous les emplacements des 3 planètes en fermes (jusqu'à 16 par planète, donc jusqu'à 7 200 cr de construction pour les 3 planètes, prime calculée en conséquence) ; l'étape est validée quand chacune des 3 planètes n'a plus que des fermes sur tous ses emplacements.

1. Logistique de la conquête (bloquant) : une barge débarque toutes les troupes qu'elle porte sur une seule planète, donc deux planètes demandent deux chargements, et seules les barges débarquent. Comment faire ? Voir la question posée.

À ne coder qu'à ton « go ».
