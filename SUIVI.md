# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Indicateur de santé faussé : fausse alerte en ligne (26/11)

Ta phrase : « j esoupsone que ton etat de santé du serveur est biaisé, sa affiche ttletpp que le serveur ne répond plus, par contre je peux naviguer si et je met un id perso qui n'existe pas en url, le serveur repond bien que le perso n'existe pas ». Compris : en ligne, l'indicateur affiche « injoignable » en permanence alors que le serveur répond. Je ne peux pas tester ton serveur, mais le code a deux défauts qui expliquent cela sur un hébergement gratuit :
- le test envoie sa requête sans cookie (`credentials: 'omit'`) : l'hébergement gratuit protège ses pages par un cookie anti-robot, et sans lui il renvoie une page intermédiaire au lieu de ma réponse ;
- la réponse est lue en JSON strict : si l'hébergeur ajoute quoi que ce soit autour de `{"ok":true}` (page intermédiaire, balise, publicité), la lecture échoue et le serveur est déclaré injoignable à tort.
À faire : (1) envoyer les cookies du site avec le test ; (2) accepter la réponse dès qu'elle contient la marque `"ok":true`, produite par ma seule route ; (3) afficher la **cause** quand l'état est « injoignable » (délai dépassé, erreur réseau, code HTTP reçu, ou réponse inattendue) dans l'infobulle de la pastille et dans la bannière, pour ne plus avoir à deviner.

## Questions en suspens

Aucune.
