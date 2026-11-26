# SUIVI

## À faire

### 1. Icônes du haut : prompts IA pour des icônes plus parlantes (26/11)

Ta phrase : « les icones du haut ( credit, gain planete... ne sont pas parlant fait generer prompt ia ».

Lu (rien codé) : la barre du haut (`inc/hud.php`) affiche 4 icônes SVG au trait, de 24 px : crédits (2 cercles), revenu cr/h (courbe qui monte), planètes (globe) et en route (fusée). Elles sont dessinées dans `Icone::DESSINS`. Les autres images du jeu (bâtiments, vaisseaux, persos) se génèrent avec Gemini : blocs STYLE / FOND MAGENTA dans `tools/prompts-images.md`, détourage par `tools/detourer-magenta.py`, et le jeu retombe sur l'icône SVG si l'image manque (`Vue::visuel`).

Ce que je comprends : écrire les prompts Gemini de 4 images (crédits, revenu, planètes, en route), dans `tools/prompts-images.md`, dans le même format (fond magenta, nom de fichier, mode d'emploi). L'affichage de ces images dans la barre du haut est une 2e étape, quand tu auras les fichiers.

Point à signaler : la barre du haut les montre à 24 px. Une image photo-réaliste y devient illisible. Il faut des objets simples, très contrastés, lisibles en miniature.

## Questions en suspens

1. Style : objet 3D simple et brillant (recommandé, ex. pile de pièces dorées, pièce avec flèche vers le haut, planète avec anneau, vaisseau avec traînée), ou pictogramme plat façon « icône d'application » ?
2. Seulement ces 4 icônes du haut, ou aussi celles du menu du bas (Carte, Colonies, Flottes, Persos, Classement) ?
