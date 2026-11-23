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

### 4. Envoi sur InfinityFree : à faire par toi (23/11)

Tes mots : « envoi le code sur ce serveur : et la db sr lui if0_43464490_xxxx ». Le paquet est prêt dans `/home/sgd/Téléchargements/spatial-infinityfree/` : `site/` (le code, aussi en `spatial-site.zip`) et `base-production.sql` (structure, 3 camps, 16 systèmes peuplés, aucun joueur). Je ne saisis pas de mot de passe : à toi de faire :

1. Importer `base-production.sql` dans phpMyAdmin, base `if0_43464490_xxxx`.
2. Renseigner le mot de passe MySQL dans `site/inc/config.php` (champ `pass`).
3. Envoyer le contenu de `site/` par FTP dans le dossier `htdocs` du site (FileZilla : hôte `ftpupload.net` selon le forum d'InfinityFree, à vérifier dans « FTP Details » du panneau ; identifiant `if0_43514686`).
4. Choisir PHP 8.2 ou plus pour le site, si le panneau le propose.

Si la base est refusée au premier chargement (message « Erreur de connexion à la BDD »), les deux comptes (`if0_43514686` et `if0_43464490`) ne sont pas liés : il faut alors créer la base depuis le compte `if0_43514686`.

### 5. Serveurs de test (23/11)

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872` restent lancés jusqu'à ta demande.

## Questions en suspens

Aucune. Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
