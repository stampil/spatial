# SUIVI

## À faire

### 1. Toutes les icônes alignées, en lignes et en colonnes (26/11)

Ta phrase : « assure toi que la totaité des icones soient aligné » (capture : la liste « Construire » : les icônes marteau et démolir ne sont pas à la même place d'une ligne à l'autre).

Lu (rien codé) : sur la capture, le texte du remboursement à droite des icônes (« +50 cr », « +100 cr », « +75 cr »…) a une largeur différente selon la ligne, et comme le groupe est collé à droite, les icônes se décalent d'une ligne à l'autre (jusqu'à 9 px). Mon correctif précédent ne réglait que la hauteur dans une même ligne.

Ce que je comprends : je donne une largeur fixe à ces textes pour que les colonnes d'icônes tombent exactement au même endroit, puis je mesure TOUTES les pages qui ont des icônes (Flottes, planète, perso, persos, carte, rapport, fenêtres) : aligné en hauteur dans chaque ligne, et aligné en colonnes dans les listes. Je corrige ce que la mesure trouve et je te donne le tableau de mesure.

### 2. Nouvelles images de planètes pour enrichir la collection (26/11)

Ta phrase : « fait generer de nouvelles image de planete pour enrichir tu peux donner ref ».

Lu (rien codé) : les planètes s'affichent avec une texture 2:1 (1440×720 ou 1024×512) qui défile en boucle sur un disque (\`img/planete/1.jpg\` à \`26.jpg\`), choisie par \`id de la planète % 27\` dans 3 pages (\`afficheSysteme\`, \`affichePlanete\`, \`listePlanete\`). Contenu actuel : géantes gazeuses (1, 5, 7, 8, 12), roches cratérisées (2, 10, 11), type Vénus (3, 4, 6), type Mars (9, 20, 21, 22, 23, 25, 26), type Terre (13, 14, 15, 19), lave (17), glace (18), rouge et blanc (16). Il manque : mondes océans, déserts de dunes, jungles, mondes exotiques violets ou verts, mondes toxiques, cristallins, volcaniques sombres, archipels, toundra, mondes de tempêtes.

Ce que je comprends : générer avec toi, une par une, de nouvelles textures dans le même format (2:1, projection plane en bande horizontale, raccord gauche-droite sans couture, vue à plat sans disque ni étoiles), et pour chaque prompt je te donne en référence une image existante à joindre dans Gemini pour garder le même rendu. Puis je les importe (\`27.jpg\`, \`28.jpg\`…) et je change le choix de la texture pour qu'il utilise toutes les images disponibles.

Précision du 26/11 : « il fau sassurer que sa fasse pas un trait au defilement que le debut corresponde a la fin ». Les images générées par Gemini ne se raccordent jamais d'elles-mêmes : le bord gauche et le bord droit sont différents, ce qui trace une ligne verticale à chaque tour de défilement. J'ai mesuré les 26 textures existantes : 8 ont une couture nette (2, 12, 16, 5, 8, 18, 15, 10).

Ce que je comprends : je fais un outil de raccord (\`tools/raccorder-planete.py\`) qui fond la fin de l'image dans son début sur une bande, pour que le dernier pixel se prolonge naturellement dans le premier ; il tourne automatiquement à l'import de chaque nouvelle texture, et je vérifie ensuite par la mesure que la couture n'est plus plus marquée qu'entre deux colonnes voisines. Aucune texture ne sera importée sans raccord vérifié.

### 3. Pluriels en dur « planétaire(s) · orbital(aux) » (26/11)

Ta phrase : « er planétaire(s) · 0 canon orbital(aux) putain d'enculer de merde __(à » (la fiche de planète affiche « 1 bouclier planétaire(s) · 0 canon orbital(aux) » au lieu d'accorder les mots avec \`__()\`).

Lu (rien codé) : la ligne \`model/affichePlanete.php:60\` accorde « bouclier » et « canon » avec \`__()\` mais écrit « planétaire(s) » et « orbital(aux) » à la main : c'est contraire à ta règle, les pluriels passent tous par \`__($cle, $clePluriel, $nombre)\`. J'ai cherché dans tout le code ce motif (\`(s)\`, \`(aux)\`, \`(x)\`) : cette ligne est la seule.

Ce que je comprends : j'ajoute les mots complets dans \`inc/lang.fr.php\` (« bouclier planétaire » / « boucliers planétaires », « canon orbital » / « canons orbitaux ») et la ligne les utilise, pour afficher « 1 bouclier planétaire · 0 canon orbital » ou « 2 boucliers planétaires · 3 canons orbitaux » sans parenthèses.

## Questions en suspens

0. Go pour corriger cette ligne ?

1. Combien : ta précision du 26/11, « fait des tellurique » : uniquement des planètes telluriques (rocheuses, à surface solide), pas de géantes gazeuses. 12 nouvelles textures (38 au total) : monde océan, dunes dorées, jungle dense, archipel, toundra, canyons rouges, plaines de sel, volcanique sombre, cendres et fissures, minéral violet, minéral vert, cratères sombres. Je recommande ces 12.
2. Le choix de la texture se fait par l'identifiant de la planète : avec plus d'images, les planètes existantes changeront d'aspect (l'univers est reconstruit à chaque remise à zéro de toute façon). Je recommande d'accepter ce changement.
3. Références : ta précision du 26/11, « pour chaque prompt tu affiche l'image de reference ! » : avec chaque prompt, j'affiche moi-même l'image de référence (je l'envoie dans la conversation pour que tu puisses l'enregistrer et la joindre dans Gemini), et je dis laquelle c'est (par exemple `15.jpg` pour un monde terrestre, `21.jpg` pour un monde rocheux de type Mars). Je recommande oui.
4. Je passe aussi l'outil sur les 8 textures existantes à couture visible (2, 12, 16, 5, 8, 18, 15, 10), pour que le défilement soit propre partout. Chacune perd environ 10 % de sa largeur, sans effet visible. Je recommande oui.
