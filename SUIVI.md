# SUIVI

## À faire

Rien en attente.

### 2. Mélanger les textures de planètes dans chaque système (27/11)

Ta phrase : « il mesemble que le moteur incremente lesplanete en modulo pourles afficher, donc pour pas les avoir dans un seule systeme, swap les de maniere homogen avec le reste du pool de planete ».

Lu : tu as raison. \`Planete::numeroTexture()\` fait \`id % 47 + 1\` et les planètes d'un système ont des identifiants qui se suivent (ex. 7740, 7741, 7742…), donc elles reçoivent des textures qui se suivent : 5 planètes de suite = 5 textures voisines dans la collection, souvent du même type (toutes les géantes gazeuses 1 à 8 ensemble, tous les mondes Terre 40 à 45 ensemble, etc.).

Ce que je comprends : on garde un choix déterministe (la même planète a toujours la même texture), mais on casse la suite : \`(id × 19) % 47 + 1\`. 19 est premier avec 50, donc deux planètes consécutives d'un même système sont toujours à 19 textures d'écart, jamais voisines, et aucune des 48 textures ne manque (répartition toujours égale). Un système de 12 planètes reçoit 12 textures toutes différentes, mélangées entre les types.

## Questions en suspens

1. Pas de 19 (premier avec 50, écart fort entre voisines). Une planète déjà affichée change de texture une fois de plus. Je recommande cela.
