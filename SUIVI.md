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

Ta phrase (25/11) : « tu n'aura pas l'adresse du site, juste tu publie quand je te le demande, sans poser de question ». Compris : je ne demande plus l'adresse du site et je ne vérifie plus le site en ligne. Publier = pousser `revival` avec `--force-with-lease` (le workflow Publish envoie le site par FTP), uniquement quand tu me le demandes, sans te poser de question avant ni après. Le site installe lui-même sa base à la première visite. Pas encore publié : le nom Novarche.

### 6. Serveurs de test (23/11)

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872` restent lancés jusqu'à ta demande.

### 7. Navigation fluide carte → système → planète (25/11)

Ta phrase : « sur la page carte quand on clique sur un ssyteme, sa doit afficher la page de ce systeme, et quand on clique sur les planete la page planete etc.. la navigation doit pouvoir etre fluid ». Compris : aujourd'hui un clic sur un système ouvre seulement une feuille en bas de la carte ; il n'existe pas de page système. À faire : une vraie page système (planètes en cartes, vaisseaux présents, camp), un clic système sur la carte y mène, un clic planète mène à la page planète, avec un fil d'Ariane Carte › Système › Planète et un retour à la carte centré sur le système. « Fluide » compris comme : transitions en fondu entre pages, sans rechargement brutal.

## Questions en suspens

**Q1 (bloquante).** Les ordres aux vaisseaux (envoyer, explorer) se donnent aujourd'hui dans la feuille de la carte. Où les mettre ? (a) sur la page planète/système, un bouton « Envoyer des vaisseaux » ouvre la carte en mode choix de destination (ma reco) ; (b) garder la feuille actuelle et ajouter un lien « Page du système ».

**Q2.** « Fluide » : (a) pages classiques + fondu entre pages (API View Transitions, aucun JS lourd) (ma reco) ; (b) chargement sans rechargement (fetch + historique), plus complexe.

Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
