# SUIVI

## À faire

### 1. Flottes : créer une flotte en cochant les vaisseaux de la planète (26/11)

Tes phrases : « créer une flotte doit etre en haut a droite de l'encart par planète, n'apparait que si 2 ou + vaisseaux elligible », puis « non, les vaisseau listé non ellibible passe en dispaly none et ceux elligible on une case a coché devant leur nom, décoché par défaut, Créer une flotte une fois 2 vaisseaux coché devient enregistrer, a coté un bouton croix pour annuler la création de flotte ».

Lu (rien codé) : aujourd'hui « Créer une flotte » est un bloc dépliable en bas de l'encart, avec une case « inclure » et un curseur par TYPE (frégate, barge, capital), et le serveur prend les premiers vaisseaux du type (`Flotte::creer`, `model/creerFlotte.php`). Éligible = frégate, barge ou capital, posé sur la planète, hors flotte.

Ce que je comprends (le curseur et le choix par type disparaissent) :
- Un bouton « Créer une flotte » en haut à droite de l'encart de la planète, présent seulement s'il y a 2 vaisseaux éligibles ou plus sur cette planète.
- Au clic, on entre en mode création : les lignes non éligibles (reconnaissance, chasseurs, troupes, transport…) sont masquées (`display: none`) ; chaque vaisseau éligible reçoit une case à cocher devant son nom, décochée.
- Le bouton devient « Enregistrer » quand 2 vaisseaux ou plus sont cochés ; à côté, une croix annule et remet l'affichage normal.
- On choisit donc des vaisseaux précis (par leur nom, ex. « Frégate 12 »), plus « les premiers du type ».

## Questions en suspens

1. Le nom de la flotte : au clic sur « Enregistrer », un champ de nom apparaît (proposé : prérempli « Flotte 1 », « Flotte 2 »…, modifiable) ? Ou un autre moyen ?
2. Avant 2 cases cochées, le bouton du mode garde-t-il le libellé « Créer une flotte » (inactif), et devient « Enregistrer » dès la 2e case ? C'est ma lecture ; sinon dis-moi.
3. Dans le mode création, les vaisseaux éligibles gardent-ils leurs boutons (Voyager, Embarquer, Renommer) ou je les masque aussi pour alléger ?
