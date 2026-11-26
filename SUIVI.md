# SUIVI

## À faire

### 1. Embarquement des troupes et des chasseurs (26/11)

Ta phrase : « Les troupes ne se deplace jamais seule, ils faut les mettre dans des vaisseaux ou barge de debarquement, Chaque type de vaisseau a une capacité d'embarquement de troupe et chasseur,  sa peut etre 0 trouve une coherence.Les chasseur peuvent se deplacer de planete en planete dans un meme systeme, mais pour sauter de systeme en sustem doivent etre embarqué dans les vaisseaux lourd. »

Compris : chaque type de vaisseau reçoit une capacité de troupes et une capacité de chasseurs (0 possible). Une troupe ne bouge qu'embarquée, même d'une planète à l'autre du même système. Un chasseur se déplace seul à l'intérieur d'un système ; pour changer de système il doit être embarqué dans un vaisseau lourd. Voir Q1.

### 2. Production d'unités fluide (26/11)

Ta phrase : « La construction des unité doit etre plus fluide, on doit povoir choisir le nombre avant de lancer la.production, et ils apparaissent en depilant sur leur temps unitaire.L'académie cap le nombre de troupe "sur la planete". »

Compris : un champ « nombre » avant de lancer ; les unités sortent une par une, chacune après son temps unitaire. L'académie plafonne le nombre de troupes présentes sur la planète (valeur par académie : 10, comme la capacité d'une barge, sauf avis contraire).

### 3. Tuto : tenir la planète capturée (26/11)

Ta phrase : « Poursuit le tuto, apres la capture de la planete, fait la tenir sous la force par l'armée  d'occupation. Les malus de revoltes sont annulé et la revolte ne se propage plus tant que des unités sont presente. »

Compris : tant que des troupes du joueur sont présentes sur la planète, la révolte est figée (les malus de révolte sont annulés, elle n'augmente plus). Le tuto continue après la capture pour l'enseigner.

### 4. Tuto complet (26/11)

Ta phrase : « Faitbun tuto complet avec toutes les mecanique, assassinat etc... »

Compris : le tuto couvre toutes les mécaniques : bâtiments, production, embarquement, flottes, exploration, capture, occupation, diplomatie, recrutement, assassinat, menaces pirates, défense.

### 5. Tuto : icônes du haut (26/11)

Ta phrase : « Dans le tuto explique ce quels les icones du haut et comment les influencer »

Compris : une étape du tuto explique crédits, revenu, planètes, vaisseaux en route de la barre du haut, et comment chacun se modifie.

### 6. Exploration sans système voisin en pointillé (26/11)

Ta phrase : « Pour l'exploration ne fait pas mechanique systeme voisin en pointillé, soit le systeme est visible et c juste recupe info des planetes en allant de planete a planete , soit le faire partir dans l'inconnu.Pour le tuto c un inconnu a 100% de reussite. »

Compris : le secteur voisin en pointillé disparaît de la carte. Système visible : on récupère les infos des planètes en s'y rendant de planète en planète. Sinon : départ dans l'inconnu (risque de perte). Tuto : l'inconnu réussit à 100 % et révèle le système voisin du tuto.

### 7. Découverte des secteurs de joueurs (26/11)

Ta phrase : « La.decouvertr secteur est tjrs celui des joueurs qui n'ont ont pas ete decouvert et eligible decouverte. Et si plus de joueur les secteur on des planete qui se repartissent entre pirate et autonome, sans clan. »

Compris : une découverte révèle toujours le secteur d'un joueur pas encore découvert et éligible (après sa protection de 7 jours). S'il n'en reste plus, un nouveau secteur est créé avec des planètes réparties entre Pirates et autonomes (sans camp), plus d'Empire dans ces secteurs. Voir Q4.

### 8. Reset serveur : répartition des camps (26/11)

Ta phrase : « Refait un reset serveur pour les galaxie cisible il en faut 25% de totalement rebele et pour le imperial, entre 0, 1 ou 2 planete rebelle, et 0 a 1 planete pirate. »

Compris : sur les systèmes visibles (16 aujourd'hui), 25 % (4) sont entièrement Rébellion ; chaque système impérial a 0, 1 ou 2 planètes rebelles et 0 ou 1 planète pirate. Les quatre systèmes « sans camp » actuels deviennent les quatre systèmes rebelles ; le reste du plan est conservé. Le reset a besoin d'un déclencheur : voir Q2.

### 9. Stats des persos ingrates (26/11)

Ta phrase : « Les stats des joueurs peuvent etre ingrate, ne fzit pas un random simple, repartie 10 point en random sur les 15 possible »

Compris : force, endurance, diplomatie ont chacune 5 étoiles possibles (15 places) ; 10 points sont posés au hasard sur ces 15 places (chaque stat de 0 à 5, total 10), pour tous les persos (premier perso et recrues).

### 10. Boutons d'action sans arrondi, design futuriste (26/11)

Ta phrase : « Ne fait pas de radius sur les btn d action fait un design futuriste »

Compris : tous les boutons d'action (`md3-button`) perdent leurs bords arrondis et prennent un style futuriste (angles coupés, liseré lumineux). Cela sort de Material Design 3 (qui prescrit des boutons arrondis) : je le fais parce que tu le demandes.

### 11. Connexion en localStorage et reconnexion automatique (26/11)

Ta phrase : « Stock la connexion en locale storage et fait un autorelogin systematique »

Compris : la connexion est gardée dans le localStorage du navigateur et la page se reconnecte seule à chaque visite. La déconnexion l'efface. Voir Q3.

### 12. Flottes : vaisseaux moyens et lourds seulement (26/11)

Ta phrase : « Les flottes ne sont que les vaisseaux qui peuvent se deplacer entre galaxie donc moyen et lourd, les vaisseau de reco peuevnt voyager entre galaxy, mais ne rejoigne pas les flottes. Une flotte est un groupe a la creation de flotte dans lencart on let tout les type de vaisseau eligible avec number a 0 et c a nous de choisir qui rejoint »

Compris : seuls les vaisseaux de l'usine moyenne et lourde (frégate, barge, capital) rejoignent une flotte. Le vaisseau de reconnaissance voyage entre systèmes mais reste hors flotte ; chasseurs et transport personnel aussi. Le formulaire de création de flotte liste tous les types éligibles présents, chacun à 0, et tu choisis les nombres.

### 13. Fenêtre du bâtiment (26/11)

Ta phrase : « Au clique de limage du bâtiment sa affiche popin qui le decris avec son imgz plus grande et qui donne les infobde ce que le batiment fait. »

Compris : un clic sur l'image d'un bâtiment ouvre une fenêtre avec l'image agrandie, son nom, son prix, sa maintenance et ce qu'il permet (types produits).

### 14. Constructions en ajax (26/11)

Ta phrase : « Les constructions doivent se faire en ajax, pas de rechargement de page, les btn action ne doivent pas engendrer de la navigation. »

Compris : tous les boutons d'action (construire, produire, recruter, diplomatie, assassinat, flottes, réponse aux menaces, ordres) s'exécutent sans changer de page : la page se met à jour sur place, les messages s'affichent sans rechargement.

### 15. Ordres aux vaisseaux depuis la vue Flottes (26/11)

Ta phrase : « Ne redirige pas sur la carte pour donner des ordre a la flotte, c dans la vue flotte qui liste les vaisseau qui doit permettre de donner les ordre et pour la navigation oui apres on choisi sur la carte la destination. »

Compris : on choisit vaisseaux, nombres ou flotte dans la vue Flottes ; seule la destination se choisit ensuite sur la carte. Le bouton « Envoyer des vaisseaux » de la page système renvoie vers la vue Flottes (bloc de la planète) au lieu d'ouvrir la carte.

## Questions en suspens

**Q1 (bloquante).** Capacités d'embarquement par type : tu n'as pas donné les chiffres. Ma proposition cohérente (troupes / chasseurs) : chasseur 0 / 0 ; reconnaissance 0 / 0 ; transport personnel 0 / 0 ; frégate (moyen) 5 / 0 ; barge (moyen) 10 / 0 ; capital (lourd) 20 / 6 ; troupe d'assaut 0 / 0. Ainsi seul le capital emmène des chasseurs (« vaisseaux lourds »), et la barge reste le transport de troupes. OK ?

**Q2 (bloquante, destructive).** Le reset serveur n'a plus de déclencheur (le bouton Migration vient d'être retiré). Je le remets le temps de ce reset, puis tu me dis de le retirer de nouveau (ma reco) ; ou autre moyen ?

**Q3 (bloquante, sécurité).** Auto-login : je stocke dans le localStorage un **jeton aléatoire par appareil** (le serveur n'en garde que l'empreinte, supprimé à la déconnexion), jamais le mot de passe en clair (ma reco). Ou le mot de passe lui-même, lisible par tout script de la page ?

**Q4.** Découverte : les systèmes rebelles visibles (cachés à tous tant qu'ils ne sont pas découverts) restent-ils découvrables par exploration, en plus des secteurs de joueurs ? Ma reco : oui, les secteurs de joueurs d'abord, puis les systèmes rebelles non découverts, puis création pirate/autonome.

Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Les choix de détail non listés ici sont appliqués selon ma reco.
