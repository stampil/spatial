# SUIVI

## À faire

### 1. Pull request, commits et push (23/11)

Ta phrase : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». La PR 1 de `revival` vers `master` est ouverte : https://github.com/stampil/spatial/pull/1. La fusion sur `master` est à toi, à la fin.

Ta phrase : « si si tu push with lease et si tu doit reprendree, tu amend ». Ta phrase : « revival c ta branche de travail a toi ». Compris : je pousse `revival` avec `--force-with-lease`, j'amende pour reprendre un travail déjà commité, un nouveau sujet fait un nouveau commit, et je ne touche jamais à `master`.

### 2. Tester avec ta base (23/11)

Avant de tester sur ta base : exécuter `database/migration_revival.sql`. Il ajoute les colonnes et crée les tables des nouvelles fonctions (camps, vaisseaux, flottes, découvertes, persos en voyage, rôles, carte avec coordonnées des systèmes, bonus de planète, départ des trajets) et note les migrations déjà appliquées. Testé sur l'ancien schéma : le résultat est identique au nouveau `database/systeme.sql`.

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

### 7. Toutes les vues comme un jeu de gestion 4X (24/11)

Ta phrase : « c pas que al carte spacial a reprendre, c designer toutes les vue comme un jeu gamer de gestion 4X ». Compris : la carte n'était qu'un début. Toutes les vues du jeu passent d'un site de pages de listes et de formulaires à une interface de jeu de gestion 4X (explorer, étendre, exploiter, exterminer), mobile d'abord, jouable au doigt. Pas de code avant ton « go ».

Ta consigne (24/11) : « affiche sur ton navigateur que je voie en temps reel les avancé design ». Compris : pendant ce chantier, je montre l'avancement dans le panneau navigateur, au format téléphone.

Ce que je propose (à valider par ton « go ») :
- **Cadre commun, en premier.** Thème sombre d'espace partout (MD3 sombre : un thème de jeu reste dans MD3 ; si une vue sort de MD3, je le dis). Barre d'état permanente en haut : crédits, revenu par heure, nombre de planètes, flottes en route. Barre de navigation en bas (déjà là), icônes Material Symbols, cartes à entête coloré par camp, jauges et comptes à rebours pour toute production, un journal d'événements à la place des bandeaux de messages. Boutons d'action carrés, sans arrondi (ta consigne).
- **Planète.** Image de la planète, grille d'emplacements de bâtiments (touche un emplacement vide pour construire), files de production avec barres de progression, défense, garnison et révolte en jauges, bonus de la planète.
- **Colonies** (liste des planètes). Une carte par planète : revenu, révolte, production en cours, alertes.
- **Flottes et chantier** (Vaisseaux). Une carte par flotte avec sa composition en icônes, son état, sa destination et son compte à rebours ; création de flotte au doigt ; lien vers la carte.
- **Personnages** (liste et fiche). Cartes façon héros : rôle, caractéristiques en jauges, état, actions.
- **Quartier général** (le tableau de bord actuel). Ressources, revenu, tutoriel présenté comme des missions à objectifs, journal d'événements.
- **Accès au jeu.** Accueil, inscription avec choix du camp en cartes de faction (histoire et avantages), connexion.
- **Classement, joueurs, camps.** Classement façon tableau de scores de jeu 4X, fiche de camp.
- **Carte et rapport de bataille** : déjà faits, raccordés au nouveau thème.
- **Pages retirées.** Les listes « Systèmes » et « Joueurs » font doublon avec la carte et le classement : je les retire (pas de code mort). Tu me dis si tu veux les garder.
- **Illustrations.** Ta réponse (24/11) : « Icônes et SVG ». Compris : icônes Material Symbols, textures de planètes déjà dans `img/planete` et emblèmes SVG simples par camp, dessinés par moi ; aucune image à fournir.
- **Code.** Seules les vues changent : les pages qui reçoivent les formulaires et les classes du jeu restent. Les textes du tutoriel qui citent un onglet sont mis à jour, et mes scripts de test des pages sont adaptés.

## Questions en suspens

Aucune. Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
