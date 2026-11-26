# SUIVI

## À faire

### 1. Flottes : « Créer une flotte » en haut à droite de l'encart de la planète, seulement avec 2 vaisseaux ou plus (26/11)

Ta phrase : « créer une flotte doit etre en haut a droite de l'encart par planète, n'apparait que si 2 ou + vaisseaux elligible ».

Lu (rien codé) : dans `model/listeVaisseau.php`, « Créer une flotte » est un bloc dépliable tout en bas de chaque encart de planète, affiché dès qu'il y a au moins UN type éligible (frégate, barge, capital). Il contient une case « inclure » + un curseur par type, le nom de la flotte et le bouton.

Ce que je comprends : un bouton « Créer une flotte » sur la ligne du titre de la planète, aligné à droite, qui n'existe que si la planète compte 2 vaisseaux éligibles ou plus (frégates + barges + capitaux, tous types confondus). Avec 0 ou 1 vaisseau, rien n'est affiché (une flotte d'un seul vaisseau n'a pas d'intérêt).

## Questions en suspens

1. Le bouton ouvre quoi : une fenêtre (popin) avec le formulaire (recommandé : tient sur mobile, même style que les infos), ou le formulaire se déplie sous le titre ?
2. « 2 ou + » se compte-t-il sur le total (1 frégate + 1 barge suffisent) ou 2 vaisseaux du même type ?
