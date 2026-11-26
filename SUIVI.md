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

## Questions en suspens

Sur quelle action as-tu vu l'erreur (quel bouton), et l'action a-t-elle quand même fait son effet ? Si l'alerte revient, le message affiche désormais la cause exacte : donne-la-moi.
