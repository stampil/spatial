# SUIVI

## À faire

### 1. Icônes du haut : prompts IA pour des icônes plus parlantes (26/11)

Ta phrase : « les icones du haut ( credit, gain planete... ne sont pas parlant fait generer prompt ia ».

Lu (rien codé) : la barre du haut (`inc/hud.php`) affiche 4 icônes SVG au trait, de 24 px : crédits (2 cercles), revenu cr/h (courbe qui monte), planètes (globe) et en route (fusée). Elles sont dessinées dans `Icone::DESSINS`. Les autres images du jeu (bâtiments, vaisseaux, persos) se génèrent avec Gemini : blocs STYLE / FOND MAGENTA dans `tools/prompts-images.md`, détourage par `tools/detourer-magenta.py`, et le jeu retombe sur l'icône SVG si l'image manque (`Vue::visuel`).

Ce que je comprends : écrire les prompts Gemini de 4 images (crédits, revenu, planètes, en route), dans `tools/prompts-images.md`, dans le même format (fond magenta, nom de fichier, mode d'emploi). L'affichage de ces images dans la barre du haut est une 2e étape, quand tu auras les fichiers.

Point à signaler : la barre du haut les montre à 24 px. Une image photo-réaliste y devient illisible. Il faut des objets simples, très contrastés, lisibles en miniature.

Précision (26/11) : « idem les autres icone, » : même traitement pour toutes les autres icônes de l'interface.

Lu (rien codé) : en plus des 4 du haut, `Icone::DESSINS` contient d'autres icônes SVG sans image : carte, persos, classement, mission, bouclier, canon, révolte, force, endurance, diplomatie, emplacements, construire, ancre, route, actions, quartier, journal, trophée, connexion, inscription (24 au total avec les 4 du haut), plus des pictogrammes purement fonctionnels : menu, son, muet, ajouter, info. Bâtiments, vaisseaux, rôles et bonus ont déjà leurs images.

## Questions en suspens

1. Style : un objet 3D simple et brillant (recommandé : pile de pièces dorées, planète avec anneau, vaisseau avec traînée, bouclier, etc.), ou un pictogramme plat façon icône d'application ?
2. Les 5 pictogrammes fonctionnels (menu, son, muet, ajouter, info) restent en SVG (recommandé : un hamburger ou un haut-parleur 3D serait moins clair) ; les 24 autres reçoivent un prompt. D'accord ?
