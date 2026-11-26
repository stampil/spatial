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

Tes phrases : « sa affiche erreur 404 pour le btn diplomatie », « 404 sur erreur /errors/404/ », puis la capture de l'onglet Réseau (Request URL `https://4x.gamer.free/[object%20HTMLInputElement]`, POST, 302) et « ton ajax c de la merde ».

Cause trouvée (ma faute, rien codé) : `js/main.js` poste vers `formulaire.action`. Quand le formulaire contient un champ nommé `action`, le navigateur renvoie ce champ (un `<input>`) au lieu de l'adresse : l'adresse devient `[object HTMLInputElement]`, l'hébergeur la redirige vers `/errors/404/`. Vérifié dans le code : 9 formulaires ont un champ `name="action"` : diplomatie (conquête, apaiser), embarquer, descendre (`affichePerso.php`), combattre, laisser, dissoudre (`listeVaisseau.php`), attaquer, débarquer (`affichePlanete.php`). Tous échouent en ajax depuis que j'ai introduit l'ajax ; les formulaires sans ce champ (construire, produire…) marchent.

Codé (à valider, non publié) : `js/main.js` poste vers `formulaire.getAttribute('action')`. Testé en navigateur sur le formulaire de diplomatie : le POST part vers `?p=faireDiplomatie`, la diplomatie démarre (« En mission », compte à rebours), aucune erreur.

## Questions en suspens

Aucune.
