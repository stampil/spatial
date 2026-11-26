# SUIVI

## À faire

### 1. Icônes alignées sur une même ligne (26/11)

Ta phrase : « aligne les icone mere je doit vraiment te dire ça ! » (capture : l'icône Chercher est plus haute que l'icône Reconnaître sur la même ligne de la page Flottes).

Lu (rien codé) : j'ai mesuré la cause : les formulaires qui contiennent un bouton icône ont une marge basse de 12 px (marge par défaut des formulaires), donc ils sont plus hauts que les liens icône voisins, et le centrage décale le lien de 6 px. Cela touche toute ligne qui mêle un formulaire et un lien (Flottes, fiche de planète, perso).

Ce que je comprends : plus aucune marge sur les formulaires des rangées d'icônes, pour que toutes les icônes d'une ligne soient exactement à la même hauteur.

## Questions en suspens

1. Alignement des icônes : go pour retirer la marge des formulaires dans les rangées d'icônes ?
