# SUIVI

## À faire

Ta demande du 26/11 (tutoriel entre le débarquement et l'assassin) : « apres le tuto de debarquement, avant assassin, fait envoyer le chasseur de reco sur la planete num 2, les chasseurs de reco, meme si il y a des vaisseaux ne provoque pas les combats auto ( mais un joueur adverse peut tres bien les attaquer ) une fois arrivé sur la planete sa l'analyse auto et donne les info de la planette ( les troupes stationner, les vaisseaux en orbite, le nb de baiment ) bref la fiche complete qui sans cela est tout en inconnu. sur cette planete il y a un gouverneur, et c'est a ce moment la que tu declenche le tuto pour l'assassiner. tant qu'un gouverneur est en place, on ne peut pas la gouverner, ni baisser le niveau de revolte. d'ailleur pour le jeu place en a 75% des planetes, il peut y avoir des gouverneur neutre. avant d'assasiner, fait debarquer les chasseur du gros vaisseau et attquer la planete 2 qui a juste 1 chasseur en orbite. fait envoyer les chasseur seulement en disant que dan un meme systeme les vaisseaux leger peuvent se deplacer, explique que ne plus avoir de vaisseau de la planete en orbite reduit fortemnt les chance de l'espion de se faire attraper. les espions peuvent aussi sabotter les batiments. il faut des batiment de generater de bouclier planetaire, tant qu'ilssont la, la flotte ne peut pas debarquer les troupe, pares avoir assasiner le gouverneur, fait saboter ce batiment. puis enchaine le tuto »

Lu (rien codé) : c'est un gros chantier de règles AVANT le tutoriel. Aujourd'hui : les assassins ne tuent que des persos de joueurs présents sur la même planète (`Perso::assassiner`) ; il n'existe ni gouverneur PNJ (le « gouverneur » d'une planète est un joueur ou 0), ni analyse des planètes (la fiche montre tout à tout joueur qui connaît le système), ni sabotage, ni générateur de bouclier (bâtiments : 3 usines, ferme, académie). La reconnaissance ne déclenche déjà pas de combat à l'arrivée (seuls les vaisseaux armés le font).

### 1. Gouverneurs PNJ sur les planètes
- Environ 75 % des planètes non tenues par un joueur ont un gouverneur PNJ (un perso avec ses étoiles), y compris des gouverneurs neutres sur les planètes sans camp.
- Tant qu'un gouverneur est en place : on ne peut pas gouverner la planète (ni par diplomatie, ni par débarquement) ni faire baisser sa révolte.
- L'assassin peut le tuer (formule d'assassinat existante, contre l'endurance du gouverneur) ; mort, la planète s'ouvre.

### 2. Analyse des planètes par la reconnaissance
- À l'arrivée sur une planète, un vaisseau de reconnaissance l'analyse automatiquement : fiche complète (garnison, vaisseaux en orbite, nombre de bâtiments, gouverneur). Sans analyse, ces champs s'affichent « inconnu ». Les reconnaissances ne déclenchent aucun combat, mais un joueur adverse peut les attaquer.

### 3. Espions : risque de capture et sabotage
- Les vaisseaux de la planète en orbite augmentent fortement le risque que l'espion (assassin) soit attrapé ; orbite vide = risque nettement réduit.
- Les espions peuvent saboter les bâtiments d'une planète.

### 4. Structures de défense : canon orbital et bouclier planétaire (nouveaux bâtiments)
Ta demande suivante (26/11) : « fait pouvoir construire des structure canon orbital et bouclier planetaire, c'est leur nombre qui impactera les stats de defense et attaque planete ».
- Deux nouveaux bâtiments constructibles sur une planète que tu gouvernes : « Canon orbital » et « Bouclier planétaire » (générateur de bouclier). Chacun occupe un emplacement, comme les autres bâtiments.
- Leur NOMBRE fait varier les stats de la planète : le bouclier maximum (et sa régénération) dépend du nombre de boucliers planétaires, le canon maximum (les dégâts renvoyés aux attaquants) du nombre de canons orbitaux. Aujourd'hui ces stats ne dépendent que du nombre d'emplacements de la planète (`Planete::BOUCLIER_PAR_SLOT`, `CANON_PAR_SLOT`).
- Tant qu'au moins un bouclier planétaire existe sur une planète, la flotte ne peut pas y débarquer ses troupes ; le sabotage en détruit un exemplaire à la fois.
- Les planètes IA reçoivent des structures à la création selon leur taille (le tutoriel fixe celles de la planète n° 2).

### 5. Tutoriel (entre le débarquement et l'assassin)
1. Envoyer le vaisseau de reconnaissance sur la planète n° 2 (même système) ; à l'arrivée l'analyse révèle le gouverneur, 1 chasseur en orbite, les bâtiments dont le générateur de bouclier.
2. Débarquer les chasseurs du gros vaisseau et attaquer l'orbite de la planète 2 en n'envoyant que les chasseurs (« dans un même système, les vaisseaux légers peuvent se déplacer ») ; explication : plus de vaisseau en orbite réduit fortement le risque de capture de l'espion.
3. Assassiner le gouverneur (assassin recruté, amené sur la planète 2).
4. Saboter le générateur de bouclier, puis enchaîner le tutoriel (débarquement possible sur la planète 2, etc.).

### 6. Planète capturée : garnison, production ralentie, retour au neutre (26/11)
Ta demande suivante : « Planète capturée et tenue.  explique que il n'y a pas besoin de diplomatie et de mettre la planete a notre cause, tant qu'il y a une garnison, les batiments produiront avec un leger malus de temps. si la garnison part ou est detruite, la planete passe neutre avec 50% de chance d'elire un gouverneur et se reconstuera ses défense ».
- Texte du tutoriel (étape qui suit la capture) : pas besoin de diplomatie ni de rallier la planète tant que la garnison y est ; ses bâtiments produisent avec un léger malus de temps.
- Règle : une planète prise par la force est « occupée » ; tant qu'elle a une garnison, ses usines produisent avec un malus de durée. Si la garnison disparaît (détruite ou partie), la planète redevient neutre (bâtiments perdus), élit un gouverneur PNJ avec 50 % de chances, et reconstruit sa défense (bouclier, canon, garnison) avec le temps.

## Questions en suspens

Pour te laisser dire « go » d'un coup, voici mes propositions par défaut :
21. Gouverneur PNJ : un perso aux étoiles tirées entre 1 et 3, créé avec la planète (75 %), pas de nouveau gouverneur après sa mort.
22. Blocages : la diplomatie de conquête, l'apaisement et le débarquement de capture sont refusés tant que le gouverneur PNJ vit ; message clair sur la fiche.
23. Analyse : « instantané » par joueur et par planète (table dédiée), rafraîchi à chaque nouvelle arrivée d'une reconnaissance ; la fiche affiche « inconnu » sinon, et « analysée il y a X » sinon.
24. Capture des espions : l'échec d'un assassinat augmente de 10 points par vaisseau de combat de la planète en orbite (plafond 50 points) ; aucun vaisseau en orbite = pas de pénalité.
25. Sabotage : action de l'assassin sur n'importe quel bâtiment de la planète où il se trouve ; chance basée sur sa force, mêmes risques de capture ; détruit un exemplaire du bâtiment choisi.
26. Structures : Canon orbital et Bouclier planétaire à 300 cr chacun, maintenance 6 cr/h, plusieurs par planète dans la limite des emplacements ; chacune ajoute à la planète l'équivalent d'un emplacement de base (+1 × `BOUCLIER_PAR_SLOT` de bouclier maximum, ou +1 × `CANON_PAR_SLOT` de canon maximum) ; elles ne comptent pas dans les emplacements « vides » ; les planètes IA d'au moins 6 emplacements ont 1 bouclier planétaire et 1 canon orbital ; un bouclier planétaire suffit à interdire le débarquement.
27. Tutoriel : 4 nouvelles étapes entre le débarquement et l'assassin ; la planète n° 2 a un gouverneur PNJ, 1 chasseur IA et un générateur de bouclier ; les joueurs en cours de tutoriel voient leur étape décalée par une migration ; un transport de persos est fourni pour amener l'assassin.
28. Malus de production des planètes occupées : +20 % de durée.
29. Retour au neutre : vérifié à chaque affichage (garnison à 0 sur une planète occupée) ; la planète perd ses bâtiments, repart à 0 de bouclier, canon et garnison, qui se reconstituent comme pour les autres planètes (10 % par heure).
