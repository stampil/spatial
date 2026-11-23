# SUIVI

## À faire

### 1. Pull request, commits et push (23/11)

Ta phrase : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». La PR 1 de `revival` vers `master` est ouverte : https://github.com/stampil/spatial/pull/1. La fusion sur `master` est à toi, à la fin.

Ta phrase : « tu fera des nouveaux commit sur revival, je fusionnerai tout sur master a la fin ». Un nouveau sujet fait un nouveau commit.

Ta phrase : « si si tu push with lease et si tu doit reprendree, tu amend ». Compris : je pousse `revival` avec `--force-with-lease`, et quand je dois reprendre un travail déjà commité, je l'amende au lieu d'empiler un commit.

Ta phrase : « revival c ta branche de travail a toi ». Compris : `revival` est ma branche de travail, je peux l'amender et la pousser avec `--force-with-lease` sans te redemander. Je ne touche jamais à `master`, et je ne fusionne pas.

### 2. Affichage dans le navigateur (23/11)

Instruction exacte : « affiche ce projet dans ton navigateur »

Compris : ouvrir l'appli dans le navigateur intégré. Ta base (port 3306) refuse l'accès root : je l'affiche donc sur une base privée dans le scratchpad (copie du projet, `127.0.0.1:19872` pour la base, `127.0.0.1:19873` pour PHP), avec un joueur de démonstration. Les serveurs restent lancés pour que tu puisses regarder ; je ne les arrête que sur ta demande, par leur PID noté dans le scratchpad.

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». Ils restent lancés : PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872`.

### 3. Peu de contenu : navigation et actions de jeu (23/11)

Instruction exacte : « c moi ou c si light que cela ? y a aucune action dans le jeu, ni meme hors jeu ( par ex se déconnecter, retour a l'accueul, pas de menu etc ) »

Compris : tu constates qu'il n'y a aucune action de jeu ni aucune navigation (déconnexion, retour à l'accueil, menu). Ce n'est pas toi : relevé dans la source, sans rien exécuter.

- Liens et formulaires de toute l'appli : 9 en tout. `welcome` vers inscription et connexion ; le tableau de bord vers les 3 listes ; `tuto_0` vers la liste des persos ; la fiche du perso vers la diplomatie ; les 2 formulaires de connexion et d'inscription.
- Aucun lien de déconnexion, d'accueil ou de retour, et aucun menu (`grep` de `deconnection`, `<nav>`, `<menu>` vide dans `model/` et `index.php`). On ne se déconnecte qu'en tapant `?p=deconnection` ou `?d=1` dans l'adresse.
- Seule action de jeu : « Faire de la diplomatie ». Elle ne fait que poser une date de fin (`Perso::setDiplomatie`, 120 s divisées par la diplomatie) : à la fin du compte à rebours, rien ne se passe, la planète n'est pas conquise.
- `gouverneur`, `nb_usine_*`, `nb_academie`, `fin_voyage_planetaire` ne sont lus par aucune page. La base n'a que 4 tables (`joueur`, `perso`, `planete`, `systeme`) : ni vaisseau, ni flotte, ni combat. Le `Readme.md` décrit bien plus que ce qui existe.

### 4. Option 2 puis option 3 de Q16 (23/11)

Ta phrase : « go pour tout mais complet pas de "pour l'instant" ». Les options 1 (menu Material Design 3) et 2 (diplomatie complète) sont faites. Avant de tester l'option 2 sur ta base : exécuter `database/migration_revival.sql` (4 colonnes ajoutées, testé sur l'ancien schéma : identique au nouveau `database/systeme.sql`). Tes réponses aux choix de design : « Oui, ces règles », « Oui, ces valeurs », « Bâtiments + persos + vaisseaux stockés ».

**Option 3. Économie, bâtiments, persos, vaisseaux :**
- Revenu de base 10 cr/h par planète gouvernée ; ferme +25 cr/h. Maintenance par heure : usine légère 2, moyenne 4, lourde 8, ferme 3, académie 5.
- Prix : usine légère 100, moyenne 200, lourde 400, ferme 150, académie 250. Construction instantanée ; démolition rend 50 %. Total de bâtiments limité à `slot`.
- Crédits calculés à la volée depuis la dernière mise à jour (nouvelle colonne `joueur.credits_maj`). Revenu net négatif : crédits plafonnés à 0 et construction bloquée.
- Académie : forme des diplomates (nouveaux persos utilisables en diplomatie, 100 cr, diplomatie 1 à 5).
- Usines : produisent chasseur, reconnaissance, transport perso (légère) ; frégate, barge (moyenne) ; vaisseau capital (lourde), avec coût et durée, listés par planète. Les prix et durées de ces vaisseaux restent à proposer : je te les soumets avec le code.
- Hors Q16, fonctionnalités à part : assassin, héros, troupes, déplacement, combat, exploration.
- Schéma : nouvelles colonnes et tables, avec un fichier de migration SQL pour ta base et `database/systeme.sql` mis à jour.

### 5. Feuille de style sobre (23/11)

Instruction exacte : « fait un css simple et sobre, mais stylé qd meme »

Compris et fait : une feuille `css/md3.css` unique en Material Design 3, valeurs de base de la spec : fond clair violet très pâle, barre d'app, titres, liens, champs de formulaire avec étiquettes, boutons pleins et tonaux, alerte d'erreur, contenu centré sur 720 px. Thème clair seulement, couleurs des noms (planète, système, joueur, perso) conservées. Pour que ça tienne : étiquettes `<label>` dans les formulaires de connexion et d'inscription, boutons `Se connecter` et `S'inscrire` à la place de `submit`, alerte d'erreur sans jQuery UI (qui n'est plus utilisé nulle part), page d'accueil avec titre et 2 boutons.

## Questions en suspens

Aucune.
