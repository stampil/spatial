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

Ajout du 26/11 : « rajoute une planete cité ville hyper industrialisé » : une 13e texture (`planete-39`) : une planète-ville entière, mégalopole industrielle continue (usines, cheminées, raffineries, réseaux, lumières orange, scories en fusion, brume de pollution), générée juste après les plaines de sel.

### 3. Planètes de type Terre avec des continents différents (26/11)

Ta phrase : « fait des planete earth like, eau, terre de foret sable neige, mais des continent different ».

Ce que je comprends : de nouvelles textures de monde habitable (océans, forêts, sable, neige) dont le dessin des continents est différent d'une planète à l'autre, pas des variantes de la même carte. Prompts prêts dans `tools/prompts-images.md` : `planete-40` à `planete-45`, un par disposition : supercontinent en croissant, trois continents très différents, monde-océan avec îles annulaires, continent en bande équatoriale, monde froid boisé et enneigé, monde sec avec mer intérieure. Elles passent par le même raccord sans couture, et le même rendu du raccord t'est montré à chaque image.

## Questions en suspens



1. Combien : ta précision du 26/11, « fait des tellurique » : uniquement des planètes telluriques (rocheuses, à surface solide), pas de géantes gazeuses. 12 nouvelles textures (38 au total) : monde océan, dunes dorées, jungle dense, archipel, toundra, canyons rouges, plaines de sel, volcanique sombre, cendres et fissures, minéral violet, minéral vert, cratères sombres. Je recommande ces 12.
2. Le choix de la texture se fait par l'identifiant de la planète : avec plus d'images, les planètes existantes changeront d'aspect (l'univers est reconstruit à chaque remise à zéro de toute façon). Je recommande d'accepter ce changement.
3. Références : ta précision du 26/11, « pour chaque prompt tu affiche l'image de reference ! » : avec chaque prompt, j'affiche moi-même l'image de référence (je l'envoie dans la conversation pour que tu puisses l'enregistrer et la joindre dans Gemini), et je dis laquelle c'est (par exemple `15.jpg` pour un monde terrestre, `21.jpg` pour un monde rocheux de type Mars). Je recommande oui.
   Ta précision du 26/11 : « la prochaine essaye sans ref sa consomme des token » : à partir de la texture 3, plus d'image de référence envoyée ni à joindre : le prompt seul, sans la phrase sur l'image jointe. Je ne renvoie une référence que si le rendu est raté.
   Ta précision du 26/11 : « affiche moi le rendu du raccord tu verifie a chaque img généré » : à chaque texture importée, je t'affiche le rendu du raccord (le bord droit collé au bord gauche, et deux copies côte à côte) avec la mesure de couture, avant de passer à la suivante.
4. Je passe aussi l'outil sur les 8 textures existantes à couture visible (2, 12, 16, 5, 8, 18, 15, 10), pour que le défilement soit propre partout. Chacune perd environ 10 % de sa largeur, sans effet visible. Je recommande oui.
5. Combien : 6 textures Terre (40 à 45), après la planète-ville en cours. Je recommande 6.
