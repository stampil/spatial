# SUIVI

## À faire

### 1. Pull request (23/11)

Ta phrase : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». La PR 1 de `revival` vers `master` est ouverte : https://github.com/stampil/spatial/pull/1. La fusion est à toi, je ne la fais pas.

### 2. Continuer sur `revival` (23/11)

Instruction exacte : « je push pas dessuite,, continue sur la branche revival »

Compris : je continue à travailler et à commiter sur `revival`, sans nouvelle branche, et je ne pousse plus tant que tu ne me le redis pas. Ton message ne dit pas sur quoi continuer : voir Q13, je n'ai rien décidé à ta place.

Pistes relevées dans la source (lecture seule), à valider :

- r9. Paramètres en tableau (`?erreur[]=x`, `email[]=`, `mdp[]=`) : `Tools::show_error_html(string)` et `Tools::crypte(string)` reçoivent un tableau et lèvent une `TypeError` non attrapée, donc une page 500. Je prévois de ne lire que des chaînes dans `connecter.php`, `inscrire.php`, `connection.php` et `inscription.php`.
- r10. Validation côté serveur : `inscrire.php` ne vérifie que `!empty`. Le `minlength="6"` et le `type="email"` n'existent que dans le HTML (`inscription.php`). Je prévois une longueur minimale (constante nommée) et `filter_var(..., FILTER_VALIDATE_EMAIL)`, avec message d'erreur sur la page d'inscription.
- r11. `inc/header.php:4` : `session_start()` sans option, donc `HttpOnly`, `SameSite` et mode strict dépendent du `php.ini`. Je prévois `session_start()` avec `cookie_httponly`, `cookie_samesite` à `Lax` et `use_strict_mode`.
- r12. `Readme.md` ne dit rien de l'installation (`grep` vide : ni config, ni PHP, ni base). Je prévois une section : PHP 8.2 minimum, `database/systeme.sql`, copie de `inc/config.example.php` en `inc/config.php`.
- r13. `index.php:56-58` charge jQuery 3.2.1 et jQuery UI 1.12.1 depuis le CDN Google. Ce sont de vieilles versions dont les suivantes corrigent des failles XSS (à confirmer). Je prévois de passer à des versions récentes après avoir vérifié qu'elles existent sur le CDN, puis de tester `js/main.js` et le compte à rebours dans le navigateur intégré.

## Questions en suspens

- **Q13.** Sur quoi continuer ? Je recommande r9, r10, r11 et r12 : changements petits et sans risque pour l'interface. r13 peut casser l'affichage, donc je le fais seulement si tu le veux.
