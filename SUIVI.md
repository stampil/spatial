# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Spinner sur les boutons d'action (26/11)

Ta phrase : « au clique des btn action, le btn affiche un spinner le temps de la reponse ajax et interdit un reclick pendant ce temps la ». Compris : aujourd'hui le bouton est seulement désactivé pendant la requête, sans signe visible. À faire : le bouton cliqué affiche un spinner (son libellé s'efface) et reste désactivé (`aria-busy`) jusqu'à la fin de l'ajax, mise à jour de la page comprise, ou jusqu'à l'échec (le spinner disparaît alors et le bouton se réactive). Les autres boutons du même formulaire sont aussi verrouillés, la touche Entrée est couverte (la soumission est interceptée), et les boutons de la carte qui envoient un ordre (« Envoyer ici », « Explorer ») ont le même comportement. Le spinner respecte la préférence « réduire les animations ».

## Questions en suspens

Aucune.
