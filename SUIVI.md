# SUIVI

## À faire

### 1. Page Flottes : visuel refait avec des icônes d'action et images générées (26/11)

Ta phrase : « ameliore le visu d ela page flotte..  utilise le premier affichage des vaisseaux de la flotte pour leur attriubuer des petites icones d'acion ( edit pour renommé ,  et embarqué pareil voyagé, carte et dissoudre des icone ( en peu plus grosse puisque relatif a l aflotte et pas au vaisseau qui la compose, fait généré image ».

Lu (rien codé) : dans le panneau d'une flotte, les actions sont des boutons icône + mot (Voyager, Carte, Dissoudre), puis des panneaux dépliables « Renommer » (flotte) ; chaque vaisseau membre montre son sprite, son nom et ses capacités, puis deux blocs dépliables texte « Embarquer » et « Renommer » (et « Débarquer »). Les icônes d'action n'existent qu'en SVG de repli (voyager, dissoudre, renommer, embarquer, décharger, attaquer…), pas en images.

Ce que je comprends :
- Chaque ligne de vaisseau membre (la première ligne visible : sprite, nom, capacités) reçoit à droite de petites icônes seules : crayon (renommer), embarquer, décharger (si des unités sont embarquées), voyager (hors flotte seulement). Un clic ouvre le même panneau (curseur, champ de nom) sous la ligne.
- La ligne d'actions de la flotte (voyager, carte, dissoudre, renommer) passe en icônes seules plus grosses (48 px au lieu de 36 px), avec infobulle et nom accessible, plus de mot.
- Les icônes sont générées par Gemini dans le style duotone des 24 précédentes, puis importées par moi : 12 prompts, un par un avec bouton « ok » : voyager, dissoudre, renommer, embarquer, décharger, chercher, reconnaître, attaquer, attaquer en flotte, débarquer (troupes), combattre, laisser. (« carte » existe déjà.) Les boutons déjà passés en « icône + mot » (combat, Flottes) gardent leur mot sauf sur la page Flottes.

## Questions en suspens

1. Sur la page Flottes, icônes seules (infobulle) pour tous les boutons d'action, ou icône + mot court pour la flotte (plus gros) et icône seule pour les vaisseaux ? Je propose : icône seule partout, 48 px pour la flotte, 36 px pour les vaisseaux.
2. Les 12 icônes : OK pour le périmètre (toutes les actions des boutons déjà passés en icône) ?
