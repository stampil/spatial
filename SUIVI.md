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

### 5. Plan du noyau dur : ce qu'il reste (24/11)

Tes mots (23/11) : « ok d'apres le readme, fait un plan de ce qu'il reste a faire pour un noyan dur ». Les phases A à D sont faites sous la forme qu'exige le tutoriel prolongé (vaisseaux et déplacements, exploration, combat et défense, troupes et capture par la force) ; le tutoriel va maintenant de l'inscription à la capture d'une planète pirate (prime de 800 crédits à l'étape 5, fin à l'étape 8). Ce que le Readme demande et qui manque encore :

- **Persos dans les transports** (transport personnel, barge) : les persos ne voyagent pas.
- **Académie : héros et assassin** (seuls le diplomate et la troupe d'assaut existent), héros (boost combat ou diplomatie).
- **But du jeu** : page de classement (plus grosse flotte, plus de planètes).

**Hors noyau** (après) : détournement de ressources par les pirates, combats entre pirates validés à la main, IA des camps (flottes et attaques des Pirates et de l'Empire), régénération de la défense des planètes.

### 6. Serveurs de test (23/11)

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872` restent lancés jusqu'à ta demande.

## Questions en suspens

Aucune. Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
