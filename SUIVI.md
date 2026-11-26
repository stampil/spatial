# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Aide « Les icônes du haut » : croix pour la supprimer à jamais (26/11)

Ta phrase : « le helperu les icones du haut, qd c ouvert tu rajoute une croix qui la supprime a jamais de l'affichage ».

Lu (rien codé) : c'est le 2e bloc repliable de `inc/tuto.php` (« Les icônes du haut », texte `Tuto::texteIcones()`), affiché pendant tout le tutoriel sous la mission.

Ce que je comprends : quand ce bloc est déplié, une croix apparaît ; au clic, le bloc disparaît et ne revient plus jamais (ni sur les autres pages, ni après reconnexion). Le bloc « Mission » n'est pas concerné.

Proposition (rien codé) : mémoriser le choix sur le COMPTE (nouvelle colonne sur `joueur` + migration + une petite page de masquage, en ajax), pour qu'il tienne sur mobile et ordinateur.

## Questions en suspens

Le choix se mémorise sur le compte (recommandé : valable partout) ou seulement sur l'appareil (plus simple, mais à refaire sur chaque appareil) ?
