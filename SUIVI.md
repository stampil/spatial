# SUIVI

## À faire

### 1. Petites icônes pour toutes les structures (26/11)

Ta phrase : « fait de ppetites icones pour toutes les structure » (avec une capture de la liste « Construire » : seuls Bouclier planétaire et Canon orbital ont une icône à gauche, les cinq autres n'en ont aucune).

Lu (rien codé) : dans la liste, chaque ligne affiche une vignette de 72 px (`img/batiment/<clé>.png`, `inc/planeteGestion.php`) placée dans un bouton qui ouvre la fenêtre de détails. Cette vignette ne s'affiche pas (la balise est écrasée à zéro dans le bouton), d'où le vide. Bouclier et Canon n'ont pas d'image de bâtiment et tombent sur leur petite icône de thème. Les icônes de repli SVG existent pour les cinq autres (usine_legere, usine_moyenne, usine_lourde, ferme, academie) mais ne sont pas utilisées ici.

Ce que je comprends : les 7 structures (3 usines, ferme, académie, bouclier planétaire, canon orbital) ont une petite icône de 28 px au même endroit, toutes du même style duotone blanc et gris foncé ; le clic sur l'icône ouvre toujours la fenêtre de détails avec la grande image. Les mêmes icônes servent aussi pour les pastilles d'emplacements et les titres des sections d'usines.

## Questions en suspens

1. Images : 5 nouvelles icônes à générer avec toi, une par une (usine légère, usine moyenne, usine lourde, ferme, académie). Bouclier planétaire et canon orbital gardent les icônes bouclier et canon déjà faites. Je recommande cela.
2. La vignette de 72 px est supprimée de la ligne (elle ne s'affichait pas) ; la grande image reste dans la fenêtre de détails. Je recommande oui.
