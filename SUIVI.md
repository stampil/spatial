# SUIVI

## À faire

### 1. Page Flottes : 3 images d'icônes d'action à régénérer (26/11)

Ta phrase d'origine : « ameliore le visu d ela page flotte.. utilise le premier affichage des vaisseaux de la flotte pour leur attriubuer des petites icones d'acion ( edit pour renommé , et embarqué pareil voyagé, carte et dissoudre des icone ( en peu plus grosse puisque relatif a l aflotte et pas au vaisseau qui la compose, fait généré image ».

Fait : page refaite, 9 images importées sur 12. Le script a refusé voyager, embarquer et laisser : un cadre clair ou blanc sur les bords, le fond n'est pas un magenta uni. Ces 3 boutons gardent l'icône SVG de repli en attendant. Reste : les régénérer en insistant sur le fond (même prompt, plus la phrase « the magenta background must fill the entire image to all four edges, no frame, no border, no margin, no white edge »), puis les importer.

### 2. Tuto : afficher les icônes des actions demandées (26/11)

Ta phrase : « ok dans le tuto tu affiche les icones relatives aux aciton demandé ».

Lu (rien codé) : les textes des étapes du tuto (`inc/class/Tuto.class.php`) nomment encore les actions avec des mots (« Chercher » (la loupe), « Embarquer », « Combattre »…) alors que les boutons de la page Flottes sont maintenant des icônes seules. Ce que je comprends : dans chaque étape, chaque action citée s'affiche avec son icône en ligne, au même rendu que le bouton (loupe, crayon, missile…), pour qu'on reconnaisse le bouton à toucher.

### 3. Vue mobile de tout ce chantier (26/11)

Ta phrase : « gere la vue mobile aussi c important ».

Lu : sur téléphone (375 px), les icônes de chaque vaisseau passent sous son nom, alignées à gauche, en 40 px, sous les 48 px recommandés pour un doigt. Ce que je comprends : la page Flottes (vaisseaux, flotte, groupes sans nom, panneaux embarquer et renommer) et les icônes du tuto doivent être conçus et vérifiés en 375 px, pas seulement sur ordinateur.

## Questions en suspens

1. Tuto : icône + mot (« [loupe] Chercher ») ou icône seule dans le texte ? Je recommande icône + mot : on apprend le nom de l'action et on retrouve l'icône.
2. Tuto : toutes les étapes qui citent une action (chercher, créer une flotte, embarquer, attaquer en flotte, débarquer, combattre…), pas seulement les nouvelles ? Je recommande toutes.
3. Mobile : icônes des vaisseaux en 48 px sur écran tactile (la flotte reste en 56 px), sous le nom, avec un écart de 8 px ? Je recommande oui.
