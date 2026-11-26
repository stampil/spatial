# SUIVI

## À faire

### 1. Icônes de l'interface : duotone au lieu de monochrome (26/11)

Ta phrase : « ok mais non pas assez de nuance, fait un duotone au lieu de monocroem ».

Lu (rien codé, rien réécrit) : les prompts actuels (commit 53b94c7) demandent une seule silhouette blanche avec des creux. Le jeu l'affiche en masque de la couleur du thème (`Icone::html`, `.icone--image`), le script (`tools/detourer-magenta.py`) garde l'opacité du détourage seulement.

Ce que je comprends : chaque glyphe a deux tons : une forme principale pleine et une forme secondaire plus claire qui donne du relief (comme les icônes « duotone » des polices d'icônes). Toujours sans couleur de teinte : les deux tons sont deux intensités de la couleur du thème.

Proposition (à valider) :
- Génération : forme principale en blanc pur, forme secondaire en gris moyen (#808080), rien d'autre (pas de dégradé).
- Le script convertit le gris en transparence partielle : blanc = opaque, gris = environ 50 % ; le jeu continue d'afficher en masque, donc deux nuances de la couleur du thème.
- Je réécris le bloc GLYPHE et les 24 prompts en indiquant, pour chacun, ce qui est en blanc (principal) et ce qui est en gris (secondaire). Les anciens prompts 1 et 2 sont à refaire.

## Questions en suspens

Le gris secondaire à environ 50 % d'opacité te va (ou plus discret, ~35 %, comme les polices d'icônes duotone) ?
