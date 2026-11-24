# SUIVI

## À faire

### 1. Pull request, commits et push (23/11)

Ta phrase : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». La PR 1 de `revival` vers `master` est ouverte : https://github.com/stampil/spatial/pull/1. La fusion sur `master` est à toi, à la fin.

Ta phrase : « si si tu push with lease et si tu doit reprendree, tu amend ». Ta phrase : « revival c ta branche de travail a toi ». Compris : je pousse `revival` avec `--force-with-lease`, j'amende pour reprendre un travail déjà commité, un nouveau sujet fait un nouveau commit, et je ne touche jamais à `master`.

### 2. Tester avec ta base (23/11)

Avant de tester sur ta base : exécuter `database/migration_revival.sql`. Il ajoute 9 colonnes (`perso` : `action_diplomatie`, `fin_prison`, `fin_mort` ; `planete` : `revolte_maj`, `id_camp` ; `joueur` : `credits_maj`, `id_camp`) et crée les tables `production`, `vaisseau` et `camp` (3 camps avec leur couleur : Pirates, Empire, Rébellion). Testé sur l'ancien schéma : le résultat est identique au nouveau `database/systeme.sql`.

Vide aussi la table `joueur` (anciens hash `crypt()`, et aucun camp pour les anciens joueurs) et renseigne `inc/config.php`.

Ensuite, peuple l'univers une seule fois : `php database/peupler.php` (16 systèmes : 6 d'Empire, 3 de Pirates, 3 disputés, 4 sans camp). Il refuse de tourner si le jeu est déjà peuplé.

### 3. Garder l'essence du développeur d'origine (23/11)

Ta phrase : « garde la structure du code l'essence du développeur qui l'a fait. ». Compris : règle pour tout le travail à venir. Le code garde la structure d'origine : une classe par objet (`Joueur`, `Perso`, `Planete`, `Systeme`), `MyPDO::getInstance()->query()` avec `MyPDO::DB_FLAG`, pages dans `model/`, pas de framework. Les idées de gameplay du `Readme.md` (empire, rébellion, pirate, combats en 3 phases, usines à slots) restent la référence : je ne les remplace pas par les miennes.

### 4. Publication sur InfinityFree : base installée par le site (23/11)

Le code est en ligne et le site installe lui-même sa base à la première visite (tables, 3 camps, 16 systèmes) : plus d'import dans phpMyAdmin. Seul le premier chargement le confirmera sur InfinityFree ; je le regarde dès que tu me donnes l'adresse du site.

### 5. Plan du noyau dur, d'après le Readme (23/11)

Tes mots : « ok d'apres le readme, fait un plan de ce qu'il reste a faire pour un noyan dur ». Compris : le « mot d'ordre simplification » du `Readme.md` donne le noyau dur = la boucle complète du jeu : produire une flotte, explorer, se battre, prendre des planètes, être classé. Rien n'est codé : c'est un plan, une phase se lance sur ton « go ».

**Ce que le Readme demande et où ça en est**

| Élément du Readme | État |
|---|---|
| Système de départ créé à l'inscription, tuto jusqu'à la prise d'une planète | fait |
| Prise de planète par diplomatie (long, risque), baisse de révolte, indépendance | fait (le diplomate meurt ou est emprisonné ; l'indépendance se déclenche sans troupe de garnison : pas encore) |
| Slots, usines légère / moyenne / lourde, ferme, académie, maintenance, revenu négatif bloquant | fait |
| Les 3 camps, leurs couleurs, planètes et systèmes par camp, peuplement | fait ; leurs effets (prix, visibilité, détournement) : non |
| Vaisseaux : chasseur, reconnaissance, transport perso, frégate, barge, capital | produits et comptés par planète ; ils ne bougent pas et ne servent à rien |
| Flottes dynamiques (n chasseurs, n frégates, n capitaux) | non |
| Reconnaissance : trouver des systèmes et les caractéristiques des planètes inconnues | non (`systeme.visible` existe et n'est jamais lu) |
| Fin du tuto : système visible aux reconnaissances des autres | non (le texte du tuto dit : 7 jours après la fin) |
| Combat en 3 phases (chasseurs, frégates, capitaux), bonus transmis | non |
| Défense planétaire (bouclier, canon) | non |
| Prise de planète par troupes d'assaut (révolte à la prise) | non |
| Académie : héros, troupe d'assaut, assassin (seul le diplomate existe) | non |
| Héros (boost combat ou diplomatie), assassin | non |
| Épaves récupérées après une victoire | non |
| But : plus grosse flotte, plus de planètes | non (pas de classement) |

**Phases proposées, dans l'ordre** (chacune : code, tests sur la base privée, un commit, un push qui publie)

- **A. Flottes et déplacements** (moyenne). Un joueur groupe ses vaisseaux d'une planète en flotte, l'envoie vers une planète (même système ou autre) ; la durée dépend du vaisseau le plus lent ; l'arrivée se résout à la volée, comme le reste ; les persos voyagent dans les transports.
- **B. Exploration et visibilité** (moyenne). Chaque joueur ne voit que ce qu'il a découvert : une reconnaissance révèle des systèmes et les caractéristiques des planètes ; le système de départ devient découvrable 7 jours après la fin du tuto ; l'Empire est toujours visible ; Pirates et Rébellion le sont seulement une fois découverts.
- **C. Défense planétaire et combat de flottes** (grosse). Les planètes d'Empire et de Pirates, et les planètes des joueurs, ont un bouclier et un canon ; combat en 3 phases comme dans le Readme ; pertes, épaves converties en crédits pour le vainqueur.
- **D. Troupes d'assaut et prise par la force** (grosse). L'académie forme la troupe d'assaut ; une barge la transporte ; combat au sol après la destruction de la défense ; la planète prise devient révoltée ; une garnison empêche l'indépendance.
- **E. Effets des camps** (petite). Unités moins chères pour l'Empire ; la visibilité de B déjà posée par camp.
- **F. But du jeu** (petite). Une page de classement : plus grosse flotte et plus de planètes.

**Hors noyau** (après) : héros et assassin, détournement de ressources par les pirates, combats entre pirates validés à la main, IA des camps (flottes et attaques des Pirates et de l'Empire).

**Choix de ma main dans ce plan** (à corriger si besoin) : les distances entre systèmes n'existent pas en base, donc durée de trajet fixe : courte dans un système, plus longue entre systèmes ; les valeurs de défense, de puissance, de vitesse, de prix des troupes, la réduction de l'Empire et la part d'épaves récupérée seront mes propositions au moment de la phase, notées dans le suivi.

### 6. Tutoriel prolongé : reste le combat, les troupes et la capture (24/11)

Tes mots : « poursuis le tuto fait créé vaisseau d'explration, fait trouver un systeme voisin, fait faire des trouve de debarquement , de vaisseau d'attaque et fait le tuto jusqu'a une capture d'une planete camps pirate ».

**Fait** (commits poussés et publiés) : l'onglet Vaisseaux et les déplacements ; les migrations numérotées de la base (le site en ligne se met à jour seul) ; l'exploration : table `decouverte`, un système voisin des Pirates créé à l'inscription et invisible tant qu'il n'est pas exploré, destination « Système voisin (inconnu) », le vaisseau de reconnaissance qui arrive explore le système ; les étapes 2 à 4 du tutoriel (usine légère, reconnaissance, exploration), avec un encadré « Tutoriel » sur chaque page, durées divisées par 5 et maintenance suspendue pendant le tutoriel. Pour l'instant le tutoriel se termine à l'étape 5 (exploration faite).

**Reste, en deux commits :**
- **Combat et troupes** : colonnes `planete.bouclier`, `planete.canon`, `planete.troupes` (garnison) ; l'académie forme la troupe d'assaut ; la barge transporte les troupes ; attaque d'une planète par une flotte posée dessus (3 phases du Readme : chasseurs, frégates, capitaux contre canon et bouclier), puis combat au sol ; en cas de victoire la planète prend le camp du joueur, elle est révoltée, les épaves rapportent des crédits.
- **Étapes finales du tutoriel** (la fin passe de 5 à 8 ; les joueurs de l'ancienne fin passent à la nouvelle sans refaire) : 5 : usine moyenne et académie, barge, 3 troupes, 3 chasseurs ; 6 : envoyer la flotte sur la planète pirate ; 7 : détruire la défense et débarquer ; 8 : capture, fin du tutoriel ; prime de 600 crédits à l'étape 5.

**Mes valeurs, à corriger si besoin** : planète pirate du tutoriel : bouclier 10, canon 5 (j'ai baissé les 20 et 10 prévus : trois chasseurs de force 5 doivent pouvoir la vaincre), garnison 3 troupes ; troupe d'assaut : 60 crédits, 20 s, force 10 ; barge : 10 troupes au maximum ; chasseur : force 5 ; le combat se résout par comparaison des forces avec un aléa de plus ou moins 20 %.

### 7. Serveurs de test (23/11)

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872` restent lancés jusqu'à ta demande.

## Questions en suspens

Aucune. Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
