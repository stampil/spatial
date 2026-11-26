# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Indicateur de santé du serveur (26/11)

Ta phrase : « le serveur est capricieux, gratuit, met un indicateur de santé de 'api et signale quadn l'api ne repond plus ». Compris :
- une route légère `?p=sante` qui répond `{"ok":true}` après un `SELECT 1` sur la base ;
- le navigateur l'interroge toutes les 30 s, et aussitôt après toute action qui échoue ;
- trois états : « OK » (réponse valide en moins de 3 s), « Lent » (réponse valide, plus de 3 s), « Ne répond plus » (délai de 10 s dépassé, erreur réseau, erreur 5xx ou page qui n'est pas la réponse attendue, comme l'écran de vérification de l'hébergeur) ; tant qu'aucune mesure n'a abouti, rien n'est affiché (pas d'état inventé) ;
- une pastille colorée dans la barre du haut, et une bannière rouge « Le serveur ne répond plus, nouvelle tentative… » tant qu'il est injoignable, qui disparaît (« Connexion rétablie ») dès qu'il revient ;
- les boutons d'action qui échouent (ajax, ordres de la carte) ne restent plus bloqués : ils se réactivent et le message d'erreur s'affiche.

## Questions en suspens

Aucune.
