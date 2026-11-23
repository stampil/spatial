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

### 4. Q16 : menu, diplomatie, actions de planète, dans cet ordre (23/11)

Instruction exacte : « fait le Q16 dans cette ordre 1, 2 3 »

Compris : faire les trois options de Q16 l'une après l'autre : (1) menu de navigation, (2) diplomatie qui aboutit, (3) premières actions sur une planète. Un commit par option sur `revival`, poussés avec `--force-with-lease` à la fin. J'ai besoin de décisions de design avant de coder : elles sont en Q17 à Q22, avec une recommandation chacune.

**Option 1. Menu de navigation** (Material Design 3, barre d'app supérieure), dans `index.php`, commun à toutes les pages :

- Connecté : titre qui renvoie au tableau de bord, puis `Systèmes`, `Planètes`, `Persos`, `Joueurs` (`listeJoueur` existe mais n'est liée nulle part), `Déconnexion`. Déconnecté : `Accueil`, `Inscription`, `Connexion`.
- Sur les pages de détail (`affichePerso`, `afficheSysteme`, `affichePlanete`, `afficheJoueur`) : un lien « Retour » vers la liste correspondante, via une table page → page parente dans `inc/const.php`.
- Feuille de style `css/md3.css` avec les valeurs de la spec m3.material.io, que je relis avec le skill `material-3` avant d'écrire.

**Option 2. Diplomatie qui aboutit.** Relevé dans la source : `js/main.js` recharge déjà la page quand le compte à rebours atteint `00:00:00`, et le commentaire de `Planete::fillSysteme` dit « tuto fera devenir gouverneur ». Je prévois, sans changement de schéma ni tâche planifiée :

- `Perso::resolveDiplomatie()` appelé par `index.php` au chargement du joueur : si `fin_diplomatie` est passée, la planète du perso n'a pas de gouverneur, alors `gouverneur` devient le joueur, puis `fin_diplomatie` repasse à `NULL`.
- Le bouton « Faire de la diplomatie » n'apparaît que sur une planète sans gouverneur.
- `affichePlanete` affiche le gouverneur, ou « Aucun ».
- La première conquête fait passer `etape_tuto` à 2 (fin du tutoriel). Le texte de fin de tutoriel est à écrire (Q20).

**Option 3. Premières actions sur une planète.** Sur une planète dont tu es gouverneur, `affichePlanete` liste les 5 bâtiments (colonnes existantes : `nb_usine_vaiss_leger`, `nb_usine_vaiss_moyen`, `nb_usine_vaiss_lourd`, `nb_usine_ressource`, `nb_academie`) avec un bouton « Construire » : coût en crédits retiré à `joueur.credits`, total limité à `slot`, formulaire POST avec jeton CSRF. Hors de cette étape, faute de table de vaisseaux et de règle chiffrée : production, revenu, coût de maintenance.

Ta phrase : « go pour tout mais complet pas de "pour l'instant" ». Compris :

- Go sur le plan ci-dessus avec mes recommandations Q17, Q18, Q19 et Q22 (Material Design 3 de base, titre `Spatial`, menu réduit à `Déconnexion` à l'étape 0, MD3 seulement sur les nouveaux éléments).
- « Complet » annule les limites que j'avais écrites : plus de « pas d'assassinat pour l'instant » (option 2) ni de « hors de cette étape : production, revenu, maintenance » (option 3). Chaque option est livrée entière, sans moignon.
- Il me faut alors des valeurs d'équilibrage que le `Readme.md` ne donne pas (chance de réussite, risque d'emprisonnement ou de mort, revenu, maintenance, coût et durée des unités). Je ne les invente pas : je te les propose en bloc avant l'option 2, et je continue dès ta réponse.

Ordre : option 1 (menu, entièrement spécifiée), puis option 2, puis option 3.

## Questions en suspens

- **Q17.** Couleurs de la barre : palette de base de la spec Material Design 3, relue sur m3.material.io. **Recommandé.** Ou une couleur de toi.
- **Q18.** Titre affiché dans la barre : `Spatial` (nom du dépôt), car le jeu n'a pas de nom dans le code. **Recommandé.** Ou ton nom.
- **Q19.** Pendant l'étape 0 du tutoriel, toutes les pages sauf la liste des persos renvoient au tutoriel : menu réduit à `Déconnexion`. **Recommandé.**
- **Q20.** Diplomatie : résolution déterministe (pas de chance, pas d'assassinat du diplomate pour l'instant), planète sans gouverneur seulement. **Recommandé : « noyau dur » du `Readme.md`.** Si oui, texte de fin de tutoriel à écrire par moi : « Vous gouvernez maintenant X. Le tutoriel est terminé. »
- **Q21.** Prix en crédits (tu pars avec 500), valeurs de **proposition** à valider ou corriger : usine de vaisseaux légers 100, moyens 200, lourds 400, ferme 150, académie 250.
- **Q22.** Style : Material Design 3 seulement sur les nouveaux éléments (barre, boutons des nouvelles actions), les pages existantes ne changent pas. **Recommandé.** Ou refonte de toutes les pages.
