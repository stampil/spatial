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

Ta phrase : « sa affiche erreur 404 pour le btn diplomatie ».

Lu dans le code (rien codé) :
- Le bouton poste vers `?p=faireDiplomatie` (`model/affichePerso.php`), et `model/faireDiplomatie.php` existe.
- Mon code ne renvoie jamais de statut 404 : pas de `http_response_code(404)`, pas de `.htaccess`. Une page inconnue répond 200 avec le texte « 404 - Page … non trouvée ».
- Donc le 404 vient de l'hébergeur (ou d'une autre adresse que celle que je crois), pas de mon PHP. Je ne sais pas encore si c'est le POST ou le rafraîchissement qui suit : le message actuel ne donne pas l'adresse.

Proposition (rien codé) : le message indique aussi l'adresse en échec (ex. « erreur HTTP 404 sur ?p=faireDiplomatie »), pour trancher POST ou rafraîchissement à la prochaine occurrence.

## Questions en suspens

1. Le message exact affiché : « Action non confirmée (erreur HTTP 404) … » ou « Action effectuée, mais la page n'a pas pu être rafraîchie (erreur HTTP 404) … » ?
2. La diplomatie démarre-t-elle quand même (compte à rebours après rechargement) ?
3. Les autres boutons (construire, produire) marchent-ils en ligne ?
