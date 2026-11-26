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

## Questions en suspens

1. Combien : 12 nouvelles textures (38 au total) : océan, dunes dorées, jungle, violet exotique, vert toxique, cristallin bleuté, volcanique sombre, archipel, toundra, tempêtes rouges, nuages roses, cendres et fissures. Je recommande 12.
2. Le choix de la texture se fait par l'identifiant de la planète : avec plus d'images, les planètes existantes changeront d'aspect (l'univers est reconstruit à chaque remise à zéro de toute façon). Je recommande d'accepter ce changement.
3. Références : je te dis pour chaque prompt quelle image existante joindre (par exemple \`img/planete/7.jpg\` pour un monde lisse, \`15.jpg\` pour un monde terrestre, \`17.jpg\` pour la lave). Je recommande oui.
