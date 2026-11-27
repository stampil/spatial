# SUIVI

## À faire

### 1. Planètes de la vue système : image de planète en rotation, pas des points (27/11)

Ta phrase : « abrutis ta de belle image de planete en rotation et tu m'afiche des gros point de merde ? ». Contexte : tu m'avais demandé un rendu d'un système pour valider l'affichage, première planète découverte, les autres non.

Lu (rien codé) : dans la vue système de la carte, chaque planète est aujourd'hui un petit disque uni de la couleur de son camp (rayon 7 px à l'écran). Les fiches affichent déjà la vraie texture de la planète, en sphère ombrée qui tourne (`avatar--planete` : texture répétée, ombre intérieure, rotation de 90 s). La carte ne les utilise pas.

Ce que je comprends : dans la vue système, chaque planète s'affiche comme sa sphère texturée qui tourne, la même que sur sa fiche (même texture, même ombrage, même rotation), à la place du disque uni. Les planètes non explorées gardent le voile noir à 75 % par-dessus la sphère (rond, qui épouse la planète). La couleur du camp passe en anneau autour de la sphère. L'étoile au centre et les systèmes de la vue galaxie ne changent pas. Cliquable comme avant.

Précision du 27/11 : « les planetes ne doivent pas toucher l'etoile putain , et baisse l'opacité de 5% ». Donc : jamais de contact entre une planète et l'étoile (première orbite écartée : rayon 100 contre 24 aujourd'hui, pas de 30 contre 14, pour une sphère de 44 px et une étoile de 56 px) ; et le voile des planètes non explorées passe de 75 % à 70 % d'opacité (je lis « 5 points », pas « 5 % de 75 »).

### 2. Étoile centrale en couleurs d'étoiles réelles (27/11)

Ta phrase : « l'etoile ( centrale ) tu la met dans des couleurs d'etoiles réelle par random, mais une etoil gris jamais vue de am vie ».

Lu (rien codé) : l'étoile au centre de la vue système est un disque de la couleur du camp du système, donc gris pour un système sans camp.

Ce que je comprends : chaque système reçoit une couleur d'étoile tirée au hasard parmi les couleurs d'étoiles réelles (bleue, blanche, jaune, orange, rouge), jamais grise. Elle sert à l'étoile centrale de la vue système.

### 3. Refaire une proposition (27/11)

Ta phrase : « refait une prop. ».

Ce que je comprends : une nouvelle proposition de l'affichage de la vue système (sphères texturées qui tournent, étoile colorée, première planète explorée, les autres voilées), montrée en maquette animée avec les vraies textures et le vrai CSS de rotation, pour que tu la valides avant tout code. Maquette : http://127.0.0.1:19931/index.html (les 6 couleurs d'étoile se choisissent en cliquant). Si tu voulais refaire autre chose, dis-le.

### 4. Taille variable des planètes, enregistrée en base (27/11)

Ta phrase : « joue un peu sur la taille de la planete, elle doivent pas avoir toute la meme taille, sa sera sauve dans sa db planete la taille, généré la premiere fois ».

Ce que je comprends : chaque planète a sa propre taille, tirée au hasard une seule fois à sa création et enregistrée en base ; la vue système en tire le diamètre de la sphère. Précision du 27/11 : « la taille influera directement sur la capacité a recevoir des structure construite ». Donc la taille détermine le nombre d'emplacements de structures de la planète (aujourd'hui `slot`, de 1 à 16, tiré au hasard à la création et déjà enregistré ; les planètes de départ ont 8). Plus la planète est grande, plus elle a d'emplacements.

## Questions en suspens

1. Taille : sphère de 44 px, première orbite à 100 unités de l'étoile et pas de 30 (maquette), donc positions de planètes recalculées par une migration ; les durées locales restent entre 20 et 60 s en adaptant le diamètre de référence. Je recommande cela.
2. Couleur du camp : anneau fin autour de la sphère, à la place du disque coloré. Je recommande cela.
3. Voile à 70 % d'opacité (75 % − 5 points) sur toute la sphère, texture devinée en transparence. Je recommande cela. Ou tu voulais 5 % relatifs, soit environ 71 % ?

4. Le hasard : couleur fixe par système (calculée à partir de son numéro, donc la même à chaque affichage et pour tous les joueurs, sans nouvelle colonne), pas retirée à chaque chargement. Je recommande cela.
5. Les couleurs, que je ne choisis pas seul : bleue #9BB0FF, blanc-bleu #CAD7FF, blanche #F8F7FF, jaune #FFE9A8, orange #FFB56C, rouge #FF8A5B (teintes de corps noir des classes O/B, A, F, G, K, M), tirées à égalité. Tu valides ces valeurs, ou tu en donnes d'autres ?
6. Les systèmes de la vue galaxie gardent la couleur de leur camp (le gris « sans camp » y reste) ; seule l'étoile centrale de la vue système change. Je recommande cela.

7. Où vit la valeur enregistrée : l'emplacement `slot` existe déjà, est déjà tiré au hasard une seule fois et enregistré. Je recommande de le garder comme valeur d'origine (aucune nouvelle colonne, aucune planète existante qui change de capacité) et d'en déduire la taille : 1 emplacement = 28 px, 16 = 62 px, progression linéaire (maquette). Inconvénient : toutes les planètes de départ (8 emplacements) ont la même taille. L'autre solution est une colonne `taille` tirée au hasard dont dériverait le nombre d'emplacements, mais cela redistribue les emplacements des planètes existantes. Laquelle ?
8. La fiche de la planète (grande sphère de 160 px) et les petites sphères des listes reflètent aussi la taille, ou seulement la carte ? Je recommande seulement la carte : les fiches gardent une taille fixe pour rester lisibles.
9. La première orbite reste calculée pour la plus grande planète possible, afin que même une sphère de 62 px ne touche pas l'étoile. Je recommande cela.

À ne coder qu'à ton « go ».
