# SUIVI

## À faire

### 1. Planètes de la vue système : image de planète en rotation, pas des points (27/11)

Ta phrase : « abrutis ta de belle image de planete en rotation et tu m'afiche des gros point de merde ? ». Contexte : tu m'avais demandé un rendu d'un système pour valider l'affichage, première planète découverte, les autres non.

Lu (rien codé) : dans la vue système de la carte, chaque planète est aujourd'hui un petit disque uni de la couleur de son camp (rayon 7 px à l'écran). Les fiches affichent déjà la vraie texture de la planète, en sphère ombrée qui tourne (`avatar--planete` : texture répétée, ombre intérieure, rotation de 90 s). La carte ne les utilise pas.

Ce que je comprends : dans la vue système, chaque planète s'affiche comme sa sphère texturée qui tourne, la même que sur sa fiche (même texture, même ombrage, même rotation), à la place du disque uni. Les planètes non explorées gardent le voile noir à 75 % par-dessus la sphère (rond, qui épouse la planète). La couleur du camp passe en anneau autour de la sphère. L'étoile au centre et les systèmes de la vue galaxie ne changent pas. Cliquable comme avant.

### 2. Étoile centrale en couleurs d'étoiles réelles (27/11)

Ta phrase : « l'etoile ( centrale ) tu la met dans des couleurs d'etoiles réelle par random, mais une etoil gris jamais vue de am vie ».

Lu (rien codé) : l'étoile au centre de la vue système est un disque de la couleur du camp du système, donc gris pour un système sans camp.

Ce que je comprends : chaque système reçoit une couleur d'étoile tirée au hasard parmi les couleurs d'étoiles réelles (bleue, blanche, jaune, orange, rouge), jamais grise. Elle sert à l'étoile centrale de la vue système.

### 3. Refaire une proposition (27/11)

Ta phrase : « refait une prop. ».

Ce que je comprends : une nouvelle proposition de l'affichage de la vue système (sphères texturées qui tournent, étoile colorée, première planète explorée, les autres voilées), montrée en maquette animée avec les vraies textures et le vrai CSS de rotation, pour que tu la valides avant tout code. Maquette : http://127.0.0.1:19931/index.html (les 6 couleurs d'étoile se choisissent en cliquant). Si tu voulais refaire autre chose, dis-le.

## Questions en suspens

1. Taille : une sphère lisible demande environ 24 à 44 px à l'écran, alors que les orbites sont aujourd'hui espacées de 14 unités. Pour qu'elles ne se chevauchent pas, j'écarte les orbites et j'agrandis la zone affichée (positions de planètes recalculées, durées locales toujours entre 20 et 60 s). Je recommande cela, avec la sphère de 32 px au zoom de départ.
2. Couleur du camp : anneau fin autour de la sphère, à la place du disque coloré. Je recommande cela.
3. Le voile à 75 % couvre toute la sphère (la texture reste devinée en transparence). Je recommande cela.

4. Le hasard : couleur fixe par système (calculée à partir de son numéro, donc la même à chaque affichage et pour tous les joueurs, sans nouvelle colonne), pas retirée à chaque chargement. Je recommande cela.
5. Les couleurs, que je ne choisis pas seul : bleue #9BB0FF, blanc-bleu #CAD7FF, blanche #F8F7FF, jaune #FFE9A8, orange #FFB56C, rouge #FF8A5B (teintes de corps noir des classes O/B, A, F, G, K, M), tirées à égalité. Tu valides ces valeurs, ou tu en donnes d'autres ?
6. Les systèmes de la vue galaxie gardent la couleur de leur camp (le gris « sans camp » y reste) ; seule l'étoile centrale de la vue système change. Je recommande cela.

À ne coder qu'à ton « go ».
