# SUIVI

## À faire

### 1. Pull request, commits et push (23/11)

Ta phrase (23/11) : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». Ta phrase (24/11) : « push et merge sur le depot ». Compris : la PR 1 de `revival` vers `master` (https://github.com/stampil/spatial/pull/1) se fusionne sur ta demande explicite seulement ; le 24/11 tu me l'as demandé, je pousse `revival` puis je fusionne.

Ta phrase : « si si tu push with lease et si tu doit reprendree, tu amend ». Ta phrase : « revival c ta branche de travail a toi ». Compris : je pousse `revival` avec `--force-with-lease`, j'amende pour reprendre un travail déjà commité, un nouveau sujet fait un nouveau commit, et je ne touche jamais à `master`.

### 2. Tester avec ta base (23/11)

Avant de tester sur ta base : exécuter `database/migration_revival.sql`. Il ajoute les colonnes et crée les tables des nouvelles fonctions (camps, vaisseaux, flottes, découvertes, persos en voyage, rôles, carte avec coordonnées des systèmes, bonus de planète, départ des trajets, journal des événements, régénération de la défense, détournement, défis pirates, raids de l'IA, suppression de l'email) et note les migrations déjà appliquées. Testé sur l'ancien schéma : le résultat est identique au nouveau `database/systeme.sql`.

Vide aussi la table `joueur` (anciens hash `crypt()`, et aucun camp pour les anciens joueurs) et renseigne `inc/config.php`.

Ensuite, peuple l'univers une seule fois : `php database/peupler.php` (16 systèmes : 6 d'Empire, 3 de Pirates, 3 disputés, 4 sans camp). Il refuse de tourner si le jeu est déjà peuplé.

Les joueurs se connectent maintenant avec leur nom (plus d'email) : la migration 013 supprime la colonne `email`, la 014 remet les Pirates en IA, la 015 crée les menaces pirates et la 016 l'exploration risquée. Les migrations s'appliquent seules à la première visite.

### 3. Garder l'essence du développeur d'origine (23/11)

Ta phrase : « garde la structure du code l'essence du développeur qui l'a fait. ». Compris : règle pour tout le travail à venir. Le code garde la structure d'origine : une classe par objet (`Joueur`, `Perso`, `Planete`, `Systeme`), `MyPDO::getInstance()->query()` avec `MyPDO::DB_FLAG`, pages dans `model/`, pas de framework. Les idées de gameplay du `Readme.md` (empire, rébellion, pirate, combats en 3 phases, usines à slots) restent la référence : je ne les remplace pas par les miennes.

### 4. Publication sur InfinityFree (23/11)

Ta phrase (25/11) : « tu n'aura pas l'adresse du site, juste tu publie quand je te le demande, sans poser de question ». Compris : je ne demande plus l'adresse du site et je ne vérifie plus le site en ligne. Publier = pousser `revival` avec `--force-with-lease` (le workflow Publish envoie le site par FTP), uniquement quand tu me le demandes, sans te poser de question avant ni après. Le site installe lui-même sa base à la première visite. Pas encore publiés : le nom Novarche et la navigation carte → système → planète.

### 6. Serveurs de test (23/11)

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872` restent lancés jusqu'à ta demande.

### 6. Pluriel des nombres (25/11)

Ta phrase : « 1 planètes j'ai horreur quand tu fait ça, un pauvre ternaire ou un if sa mange pas de pain, mais gere ce pluriel ! ». Compris : sur la page système, « 1 planètes » s'affiche au singulier incorrect. À faire : une fonction unique `Tools::pluriel($nombre, $singulier, $pluriel)` (« 0 » et « 1 » au singulier, comme en français), côté PHP, et son équivalent côté `carte.js`. Je l'applique à tous les endroits du jeu qui ont le même défaut, pas seulement la page système : « planète(s) » (classement, fiche joueur), « planètes » et « emplacements » (pages système, planète, colonies), « vaisseau(x) », « troupe(s) » (y compris l'accord du verbe : « sont perdues »), « perso(s) », « déplacement(s) » de la carte, et les messages de combat/expédition. Le `(s)` entre parenthèses disparaît partout.

Ta correction : « non enrichi la collone de traduction et en 2ieme param c le mot au pluriel ». Ta précision : « __() ». Vérifié dans `ehowww` (`src/attic/…/locales/lang.php`) : `__($key)` renvoie `$GLOBALS['langs'][$key]`, les traductions étant la colonne de `lang.fr.php` (clé => texte). Compris : Novarche reçoit le même mécanisme, un fichier de traduction français (clé => mot, `planete` => `planète`, `planetes` => `planètes`, `emplacement`, `emplacements`, `vaisseau`, `vaisseaux`, `troupe`, `troupes`, `perso`, `persos`, `deplacement`, `deplacements`…) et `__($cle, $clePluriel, $nombre)` : le 2ᵉ paramètre est la clé du mot au pluriel, choisi quand `$nombre` > 1 (« 0 » et « 1 » au singulier). Appel : `__('planete', 'planetes', $nombre)`. Côté `carte.js`, le dictionnaire est passé par le PHP dans les données de la carte. Je ne crée pas de fichier de langue anglais (aucun besoin exprimé).

### 7. Bouton Migration dans le menu du haut (25/11)

Ta phrase : « j'execute rien sur le serveur, fait dnas le menu header un btn migration et au clique sa lance ton sql ». Compris : plus aucune commande à passer sur le serveur ; un bouton « Migration » lance `database/migration_revival.sql` (puis la suite d'installation : migrations 011 à 016, univers). Remplace la consigne du point 2 (« exécuter `database/migration_revival.sql` », vider `joueur`, `php database/peupler.php`).

Ta phrase : « je t'ia donner les acces db il me semble, tu peut pas attauqer la db du serveur ? ». Vérifié : non. Le seul mot de passe de la base du serveur est celui que `tools/connect-hosting.sh` envoie directement dans les secrets GitHub (`DB_PASSWORD`) : il ne se relit pas, je ne l'ai jamais vu. Le `inc/config.php` d'ici pointe sur une base locale de test. Je n'ai donc aucun accès à la base du serveur, d'où le bouton. Ta phrase : « quoi que » : je n'ai pas compris ce que tu voulais dire.

Ta réponse à « supprimer les anciens comptes ? » : « Q1 oui y a que emoi qui test pour l'instant surle serveur ». Compris : le bouton supprime tous les anciens comptes joueurs (et ce qui en dépend) puis migre, avec une confirmation. Ta réponse sur la visibilité : « Q2 laissse le sans logique de suppression mais au prochain commit tu l'lelve ». Compris : le bouton reste affiché en permanence, sans logique qui le masque, et le **prochain commit le supprime** (bouton, page minimale et code de migration) une fois que tu auras migré. Garde-fou que je garde malgré tout, côté action et non côté affichage : un clic sur une base déjà migrée ne supprime rien et ne rejoue rien (message « Base déjà migrée »), sinon un clic de trop effacerait les vrais comptes.

Deux pièges que j'ai vus et que je traite :
- **Le menu n'existe pas encore.** Sur la base d'avant (table `joueur` présente, pas de table `camp`), `index.php` s'arrête avec « La base est incomplète : importer database/migration_revival.sql » avant d'afficher quoi que ce soit : ni menu, ni connexion possible. Le bouton doit donc vivre dans une page minimale (barre du haut + bouton) affichée à la place de ce message.
- **Le SQL n'est pas rejouable** (`ADD COLUMN` deux fois = échec). Le bouton ne doit exister que tant que la base est ancienne, et tourner sous le verrou d'installation.

## Questions en suspens

Aucune. Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
