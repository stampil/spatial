# SUIVI

## À faire

### 1. Icônes de l'interface : style sobre, épuré, sans couleur (26/11)

Ta phrase : « ok mais non le style jure avec l'actuel, faut que sa soit sobre et epuré, sans couleur ».

Lu (rien codé, rien réécrit) : les prompts déjà écrits (`tools/prompts-images.md`, section « Icônes de l'interface », commit 044162f) demandent des icônes plates en couleurs (pièces dorées, flèche verte, planète turquoise…), avec contour marine. Elles jurent avec les icônes actuelles : des pictogrammes au trait, d'une seule couleur (celle du thème), 2 px, bouts arrondis.

Ce que je comprends : refaire le bloc ICÔNE et les 24 prompts en monochrome : un seul trait de même épaisseur, aucune couleur, aucun remplissage décoratif, formes très simples, comme les icônes actuelles mais plus parlantes. Mes prompts n'auraient plus aucun mot de couleur.

Proposition (à valider) : pour garder l'unité avec le reste, l'image générée est blanche sur fond magenta ; le jeu l'affiche comme un masque (`mask-image`), donc elle prend la couleur du thème comme les SVG actuels (violet clair ici, gris en secondaire, etc.), au lieu d'une image figée. Les 3 premiers prompts (crédits, revenu, planètes) sont à refaire ; ne génère rien avant.

## Questions en suspens

1. Trait fin au contour (comme les SVG actuels : lignes de 2 px, intérieur vide) ou silhouette pleine unie (plus lisible à 24 px, mais moins proche de l'existant) ?
2. Affichage en masque coloré par le thème (recommandé) : d'accord ?
