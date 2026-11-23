# SUIVI

## À faire

### 1. Pull request et commits (23/11)

Ta phrase : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». La PR 1 de `revival` vers `master` est ouverte : https://github.com/stampil/spatial/pull/1.

Ta phrase : « je push pas dessuite,, continue sur la branche revival ». Je ne pousse plus tant que tu ne me le redis pas.

Ta phrase : « tu fera des nouveaux commit sur revival, je fusionnerai tout sur master a la fin ». Compris : désormais un nouveau commit par sujet sur `revival`, plus d'`--amend`, et la fusion sur `master` est à toi à la fin.

Un point à savoir : le commit amendé 6da6b79, fait avant cette consigne, diffère de celui poussé (943a8dc). Le prochain push demandera un `--force-with-lease`.

### 2. Pistes restantes (23/11)

- r13. `index.php:56-58` charge jQuery 3.2.1 et jQuery UI 1.12.1 depuis le CDN Google. Ce sont de vieilles versions dont les suivantes corrigent des failles XSS (à confirmer). Je prévois de passer à des versions récentes après avoir vérifié qu'elles existent sur le CDN, puis de tester `js/main.js` et le compte à rebours dans le navigateur intégré. Tu ne l'as pas demandé : je ne le fais pas sans ton accord.

### 3. Affichage dans le navigateur (23/11)

Instruction exacte : « affiche ce projet dans ton navigateur »

Compris : ouvrir l'appli dans le navigateur intégré. Ta base (port 3306) refuse l'accès root : je l'affiche donc sur une base privée dans le scratchpad (copie du projet, `127.0.0.1:19872` pour la base, `127.0.0.1:19873` pour PHP), avec un joueur de démonstration. Les serveurs restent lancés pour que tu puisses regarder ; je ne les arrête que sur ta demande, par leur PID noté dans le scratchpad.

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». Ils restent lancés : PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872`.

## Questions en suspens

Aucune.
