# SUIVI

## À faire

### 1. Pull request, commits et push (23/11)

Ta phrase : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». La PR 1 de `revival` vers `master` est ouverte : https://github.com/stampil/spatial/pull/1. La fusion sur `master` est à toi, à la fin.

Ta phrase : « si si tu push with lease et si tu doit reprendree, tu amend ». Ta phrase : « revival c ta branche de travail a toi ». Compris : je pousse `revival` avec `--force-with-lease`, j'amende pour reprendre un travail déjà commité, un nouveau sujet fait un nouveau commit, et je ne touche jamais à `master`.

### 2. Tester avec ta base (23/11)

Avant de tester sur ta base : exécuter `database/migration_revival.sql`. Il ajoute les colonnes et crée les tables des nouvelles fonctions (camps, vaisseaux, flottes, découvertes, persos en voyage, rôles) et note les migrations déjà appliquées. Testé sur l'ancien schéma : le résultat est identique au nouveau `database/systeme.sql`.

Vide aussi la table `joueur` (anciens hash `crypt()`, et aucun camp pour les anciens joueurs) et renseigne `inc/config.php`.

Ensuite, peuple l'univers une seule fois : `php database/peupler.php` (16 systèmes : 6 d'Empire, 3 de Pirates, 3 disputés, 4 sans camp). Il refuse de tourner si le jeu est déjà peuplé.

### 3. Garder l'essence du développeur d'origine (23/11)

Ta phrase : « garde la structure du code l'essence du développeur qui l'a fait. ». Compris : règle pour tout le travail à venir. Le code garde la structure d'origine : une classe par objet (`Joueur`, `Perso`, `Planete`, `Systeme`), `MyPDO::getInstance()->query()` avec `MyPDO::DB_FLAG`, pages dans `model/`, pas de framework. Les idées de gameplay du `Readme.md` (empire, rébellion, pirate, combats en 3 phases, usines à slots) restent la référence : je ne les remplace pas par les miennes.

### 4. Publication sur InfinityFree : base installée par le site (23/11)

Le code est en ligne et le site installe lui-même sa base à la première visite (tables, 3 camps, 16 systèmes) : plus d'import dans phpMyAdmin. Seul le premier chargement le confirmera sur InfinityFree ; je le regarde dès que tu me donnes l'adresse du site.

### 5. Hors noyau dur, pour plus tard (24/11)

Ta phrase (24/11) : « go fini le noyau dur ». Le noyau dur est fini. Ce qui reste est hors noyau et attend ton « go » : détournement de ressources par les pirates, combats entre pirates validés à la main, IA des camps (flottes et attaques des Pirates et de l'Empire), régénération de la défense des planètes.

### 6. Serveurs de test (23/11)

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872` restent lancés jusqu'à ta demande.

### 7. Interface graphique de jeu inspirée d'Empire at War, mobile d'abord (24/11)

Ta phrase : « renseigne toi de coment le jeu vidéo star wars empire at war design ce genre e chose, c l'inspiration du moteur, inspire toi du design, mais sa doit etre joauble en interface graphique de jeu, adapté egalement sur mobile ( qui sera le periph principal ) ». Compris : le jeu passe de pages de listes et de formulaires à une vraie interface de jeu graphique, pensée pour le téléphone d'abord, avec le design d'Empire at War comme modèle. Pas de code avant ton « go ».

Ce que j'ai lu sur Empire at War (recherche web, sources en fin d'article) :
- Deux niveaux : une carte galactique (campagne « Galactic Conquest ») où l'on gère les planètes, construit et déplace les flottes, et des batailles tactiques en temps réel dans l'espace ou au sol.
- Toute la construction se fait sur la carte galactique, jamais pendant les batailles.
- Les planètes possédées rapportent des crédits par jour, et chacune donne un avantage (exemple cité : Kuat réduit de 25 % le prix des Star Destroyers).
- Technologie : l'Empire monte de niveau en construisant des centres de recherche, la Rébellion en volant la technologie de l'Empire avec des espions.
- Héros (Dark Vador, Han Solo) : une seule unité puissante, avec des capacités sur la carte et en bataille.
- Je n'ai rien trouvé sur le brouillard de guerre de la carte galactique : je ne l'affirme pas.

Ce que le jeu a déjà de comparable : camps, planètes à crédits, usines, flottes, héros, assassin, découverte des systèmes, durée de trajet. Ce qui manque : la carte. Un système n'a pas de position (table `systeme` : id, nom, visible, creato) et la durée d'un trajet est la même pour tous les systèmes, sans distance.

Proposition de design (à valider) :
- **Carte galactique** : écran principal, en SVG, systèmes placés par coordonnées, couleur du camp, flottes en mouvement qui bougent vers leur destination en temps réel, systèmes inconnus cachés. Zoom et déplacement au doigt.
- **Mobile d'abord** : barre de navigation en bas (carte, flottes, planètes, persos, classement), fiche d'un système ou d'une planète dans une feuille qui monte du bas (M3 bottom sheet), cibles tactiles de 48 px minimum, ordres en deux touches (choisir la flotte, toucher la destination).
- **Batailles** : résolues automatiquement (3 phases du Readme), avec un écran de rapport animé. Pas de temps réel : le jeu est en PHP sur pages.
- **Technique** : PHP et pages d'origine conservés, JS simple (SVG et fetch), pas de framework ; les pages actuelles restent en repli.

Tes réponses (24/11) :
1. « oui » : coordonnées ajoutées à la table `systeme` (migration 009), les 16 systèmes de départ placés à la main, un nouveau système de joueur placé par le code près de son voisin.
2. « oui » : la durée d'un trajet dépend de la distance sur la carte.
3. « libre » : déplacement libre vers n'importe quel système connu, pas de routes imposées.
4. « precise », puis « Dans ce chantier » : les bonus de planète sont dans ce chantier, avec la carte. Tu n'as pas donné de liste : je prends la mienne (ci-dessous) et tu reviens si un bonus ne va pas.

Question 4 précisée : avantages de planète. Dans Empire at War, posséder une planète précise donne un bonus (Kuat : Star Destroyers à −25 %). Chez nous, chaque planète des 16 systèmes de départ recevrait un bonus fixe, visible sur la carte et dans sa fiche, actif tant qu'un joueur la gouverne. Exemples, à titre d'idée : « chantier » (vaisseaux capitaux −25 %), « forge de chasse » (chasseurs −25 %), « marché » (revenu +20 %), « citadelle » (défense +25 %), « relais » (trajets −25 % au départ de cette planète). Ça ajoute une raison de se battre pour une planète précise, mais ça ajoute une colonne en base, des règles de prix et un affichage de plus. Décidé : dans ce chantier, avec la liste d'exemples ci-dessus.

Sources : [Wikipedia](https://en.wikipedia.org/wiki/Star_Wars:_Empire_at_War), [GameSpot, premier aperçu de la carte galactique](https://www.gamespot.com/articles/star-wars-empire-at-war-exclusive-hands-on-galactic-map-space-battles-darth-vader-crushes-all/1100-6140634/), [GameSpot, aperçu et détails](https://www.gamespot.com/pc/strategy/starwarsrts/preview_6116584.html), [Material Design, bottom sheets](https://m1.material.io/components/bottom-sheets.html), [Android, taille des cibles tactiles](https://support.google.com/accessibility/android/answer/7101858).

## Questions en suspens

Aucune. Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
