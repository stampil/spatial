# SUIVI

## À faire

### 1. Pull request et commits (23/11)

Ta phrase : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». La PR 1 de `revival` vers `master` est ouverte : https://github.com/stampil/spatial/pull/1.

Ta phrase : « je push pas dessuite,, continue sur la branche revival ». Je ne pousse plus tant que tu ne me le redis pas.

Ta phrase : « tu fera des nouveaux commit sur revival, je fusionnerai tout sur master a la fin ». Compris : désormais un nouveau commit par sujet sur `revival`, plus d'`--amend`, et la fusion sur `master` est à toi à la fin.

Un point à savoir : le commit amendé 6da6b79, fait avant cette consigne, diffère de celui poussé (943a8dc). Le prochain push demandera un `--force-with-lease`.

### 2. Pistes restantes (23/11)

- r13. `index.php:56-58` charge jQuery 3.2.1 et jQuery UI 1.12.1 depuis le CDN Google. Ce sont de vieilles versions dont les suivantes corrigent des failles XSS (à confirmer). Je prévois de passer à des versions récentes après avoir vérifié qu'elles existent sur le CDN, puis de tester `js/main.js` et le compte à rebours dans le navigateur intégré. Tu ne l'as pas demandé : je ne le fais pas sans ton accord.
- r14. Code mort dans `MyPDO.class.php` : la création automatique de la base (`USE`, puis `CREATE DATABASE` et `echo 'base ... créée'`) n'est jamais atteinte. Vérifié sur une base privée : avec une base inconnue, le DSN contient `dbname`, donc le constructeur de PDO échoue avant avec « Unknown database » et le script s'arrête sur `exit('Erreur de connexion ...')`. Je prévois de supprimer ces lignes.

## Questions en suspens

- **Q14.** r14 : je le fais ? Je recommande oui. r13 : je ne le fais que si tu le veux, car il peut casser l'affichage.
