# SUIVI

## À faire

### 1. Pull request, commits et push (23/11)

Ta phrase : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». La PR 1 de `revival` vers `master` est ouverte : https://github.com/stampil/spatial/pull/1. La fusion sur `master` est à toi, à la fin.

Ta phrase : « si si tu push with lease et si tu doit reprendree, tu amend ». Ta phrase : « revival c ta branche de travail a toi ». Compris : je pousse `revival` avec `--force-with-lease`, j'amende pour reprendre un travail déjà commité, un nouveau sujet fait un nouveau commit, et je ne touche jamais à `master`.

### 2. Tester avec ta base (23/11)

Avant de tester sur ta base : exécuter `database/migration_revival.sql`. Il ajoute 7 colonnes (`perso` : `action_diplomatie`, `fin_prison`, `fin_mort` ; `planete` : `revolte_maj` ; `joueur` : `credits_maj`) et crée les tables `production` et `vaisseau`. Testé sur l'ancien schéma : le résultat est identique au nouveau `database/systeme.sql`.

Vide aussi la table `joueur` (anciens hash `crypt()`) et renseigne `inc/config.php`.

### 3. Serveurs de test (23/11)

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872` restent lancés jusqu'à ta demande.

## Questions en suspens

Aucune. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
