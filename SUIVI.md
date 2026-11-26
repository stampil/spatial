# SUIVI

## À faire

### 1. Caractéristiques des persos en demi-étoiles (26/11)

Ta phrase : « pour les etoiles sa peut etre des demis etoile dans la repartition des caract de perso ».

Lu (rien codé) : à la création d'un perso, `Perso::etoilesAleatoires` répartit 10 points entiers sur Force, Endurance et Diplomatie (5 au plus chacune), stockés en entiers (colonnes `PV`, `FO`, `diplomatie`). Ces valeurs entières servent aux formules : chance d'assassinat et de sabotage (Force), durée et chance de diplomatie, apaisement de la révolte (Diplomatie), et au texte « N/5 étoiles » de la fiche. Une demi-étoile n'existe donc pas aujourd'hui.

Ce que je comprends : la répartition se fait par pas d'une demi-étoile (par exemple 2,5 ou 3,5 étoiles), et les étoiles affichées montrent une étoile entière, une moitié ou une vide.

## Questions en suspens

1. Données : les valeurs deviennent des demi-étoiles (0 à 10 par caractéristique, total de 20 demi-points = les mêmes 10 étoiles qu'avant). Les formules lisent la valeur en étoiles (demi-points ÷ 2), donc l'équilibre ne change pas. Je recommande cela.
2. Persos déjà créés : une migration double leurs valeurs (3 étoiles deviennent 6 demi-étoiles, donc toujours 3 étoiles), pour qu'aucun perso ne change de force. Je recommande oui.
3. Affichage : « 2,5/5 étoiles » dans le texte de la fiche, étoile à moitié remplie dans la rangée. Je recommande oui.
