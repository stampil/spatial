# SUIVI

## À faire

### 1. Petites icônes pour toutes les structures (26/11)

Ta phrase : « fait de ppetites icones pour toutes les structure » (avec une capture de la liste « Construire » : seuls Bouclier planétaire et Canon orbital ont une icône à gauche, les cinq autres n'en ont aucune).

Lu (rien codé) : dans la liste, chaque ligne affiche une vignette de 72 px (`img/batiment/<clé>.png`, `inc/planeteGestion.php`) placée dans un bouton qui ouvre la fenêtre de détails. Cette vignette ne s'affiche pas (la balise est écrasée à zéro dans le bouton), d'où le vide. Bouclier et Canon n'ont pas d'image de bâtiment et tombent sur leur petite icône de thème. Les icônes de repli SVG existent pour les cinq autres (usine_legere, usine_moyenne, usine_lourde, ferme, academie) mais ne sont pas utilisées ici.

Ce que je comprends : les 7 structures (3 usines, ferme, académie, bouclier planétaire, canon orbital) ont une petite icône de 28 px au même endroit, toutes du même style duotone blanc et gris foncé ; le clic sur l'icône ouvre toujours la fenêtre de détails avec la grande image. Les mêmes icônes servent aussi pour les pastilles d'emplacements et les titres des sections d'usines.

## Questions en suspens

0. Alignement des icônes : go pour retirer la marge des formulaires dans les rangées d'icônes ?

1. Images : 5 nouvelles icônes à générer avec toi, une par une (usine légère, usine moyenne, usine lourde, ferme, académie). Bouclier planétaire et canon orbital gardent les icônes bouclier et canon déjà faites. Je recommande cela.
2. La vignette de 72 px est supprimée de la ligne (elle ne s'affichait pas) ; la grande image reste dans la fenêtre de détails. Je recommande oui.

### 2. Icônes alignées sur une même ligne (26/11)

Ta phrase : « aligne les icone mere je doit vraiment te dire ça ! » (capture : l'icône Chercher est plus haute que l'icône Reconnaître sur la même ligne de la page Flottes).

Lu (rien codé) : j'ai mesuré la cause : les formulaires qui contiennent un bouton icône ont une marge basse de 12 px (marge par défaut des formulaires), donc ils sont plus hauts que les liens icône voisins, et le centrage décale le lien de 6 px. Cela touche toute ligne qui mêle un formulaire et un lien (Flottes, fiche de planète, perso).

Ce que je comprends : plus aucune marge sur les formulaires des rangées d'icônes, pour que toutes les icônes d'une ligne soient exactement à la même hauteur. Je le fais à ton « go ».
