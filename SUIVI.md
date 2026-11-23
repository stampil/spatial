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

## Questions en suspens

Aucune.
