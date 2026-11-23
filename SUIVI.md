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

## Questions en suspens

- **Q16.** Par quoi commencer ?
  - (1) Un menu de navigation commun à toutes les pages : accueil, listes, déconnexion, et « retour » sur les pages de détail. Selon ta règle UI, je suivrais Material Design 3 (barre d'app supérieure). **Recommandé en premier : petit, et ça rend le reste utilisable.**
  - (2) Faire aboutir la diplomatie : à la fin du compte à rebours, la planète passe sous ton contrôle. Il me faut tes règles (chance de réussite, risque d'assassinat du diplomate, effet de la révolte), car le `Readme.md` ne les chiffre pas.
  - (3) Premières actions sur une planète (usines, ferme, académie) : gros chantier, à découper avec toi.
