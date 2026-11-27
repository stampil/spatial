# SUIVI

## À faire

### 1. Planètes de la vue système : image de planète en rotation, pas des points (27/11)

Ta phrase : « abrutis ta de belle image de planete en rotation et tu m'afiche des gros point de merde ? ». Contexte : tu m'avais demandé un rendu d'un système pour valider l'affichage, première planète découverte, les autres non.

Lu (rien codé) : dans la vue système de la carte, chaque planète est aujourd'hui un petit disque uni de la couleur de son camp (rayon 7 px à l'écran). Les fiches affichent déjà la vraie texture de la planète, en sphère ombrée qui tourne (`avatar--planete` : texture répétée, ombre intérieure, rotation de 90 s). La carte ne les utilise pas.

Ce que je comprends : dans la vue système, chaque planète s'affiche comme sa sphère texturée qui tourne, la même que sur sa fiche (même texture, même ombrage, même rotation), à la place du disque uni. Les planètes non explorées gardent le voile noir à 75 % par-dessus la sphère (rond, qui épouse la planète). La couleur du camp passe en anneau autour de la sphère. L'étoile au centre et les systèmes de la vue galaxie ne changent pas. Cliquable comme avant.

## Questions en suspens

1. Taille : une sphère lisible demande environ 24 à 40 px à l'écran, alors que les orbites sont aujourd'hui espacées de 14 unités. Pour qu'elles ne se chevauchent pas, j'écarte les orbites et j'agrandis la zone affichée (positions de planètes recalculées, durées locales toujours entre 20 et 60 s). Je recommande cela, avec la sphère de 32 px au zoom de départ.
2. Couleur du camp : anneau fin autour de la sphère, à la place du disque coloré. Je recommande cela.
3. Le voile à 75 % couvre toute la sphère (la texture reste devinée en transparence). Je recommande cela.

À ne coder qu'à ton « go ».
