# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Ajax : erreur « serveur n'a pas répondu » immédiate alors que le serveur répond (26/11)

Ta phrase : « Go (a)+(b)+(c) » : codé, à valider.

Ta phrase : « y a encore un pb le serveur répond mais l'ajax lui par direct en erreur en disant que le serveur n'a pas répondu, sans mee lui laisser l tps de repondre ».

Codé (à valider, non publié) dans `js/main.js` et `js/carte.js` :
- (a) Le message dit la vraie cause : « échec réseau », « erreur HTTP N » ou « page illisible : … ». Même chose pour l'envoi d'ordre sur la carte.
- (b) Action réussie mais page non rafraîchie : un second essai, puis message « Action effectuée, mais la page n'a pas pu être rafraîchie (cause) : rechargement… » et rechargement de la page après 3 s.
- (c) Rafraîchissement déjà en cours à la fin de l'action : on attend sa fin puis on rafraîchit.

### 3. Bouton diplomatie : erreur 404 (26/11)

Ta phrase : « sa affiche erreur 404 pour le btn diplomatie », puis « 404 sur erreur /errors/404/ ».

Lu (rien codé) :
- `/errors/404/` est l'adresse FINALE : l'hébergeur a redirigé la requête vers sa propre page d'erreur 404. Mon message n'affiche que cette adresse finale, pas celle demandée au départ : je ne sais donc toujours pas si c'est le POST vers `?p=faireDiplomatie` ou le rafraîchissement.
- Mon `.htaccess` (`tools/package.sh`) refuse `inc/`, `model/`, `database/` en accès direct : cela donnerait un 403, pas un 404.
- Mon PHP ne renvoie jamais 404 : seul l'hébergeur le fait.

Proposition (rien codé) : en cas de redirection, le message donne les deux adresses (demandée et finale), ex. « erreur HTTP 404 sur /errors/404/ (demandé : /?p=faireDiplomatie) ».

## Questions en suspens

1. Les autres boutons (construire, produire) marchent-ils en ligne ?
2. La diplomatie démarre-t-elle quand même (compte à rebours après rechargement) ?
3. Quelle adresse as-tu dans la barre du navigateur quand tu cliques (je n'ai pas l'adresse du site) ?
