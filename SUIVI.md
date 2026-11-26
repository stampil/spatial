# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Carte : bandeau d'ordre en bas, sur une ligne (26/11)

Ta phrase : « le gros popin qui prend totue la place Vaisseau de reconnaissance touchez le syste.. tu me le vire du centre , tu le met en bas en haut du menu sur une ligne: choisir le systeme annuler ( en lien text pas btn ) ».

Lu (rien codé) : le bandeau est un bloc posé en haut de la carte (`.carte__bandeau`, `top: 72px`) avec le texte « <nom> : touchez le système de destination. » et un vrai bouton « Annuler » (`model/carte.php`, `css/carte.css`, `js/carte.js`).

Ce que je comprends : une fine ligne collée en bas de la carte, juste au-dessus de la barre de menu : « <nom> : choisir le système · Annuler », « Annuler » en lien texte (pas un bouton), tout sur une seule ligne. Sur mobile, le nom se coupe avec « … » plutôt que de passer à la ligne ; « Annuler » reste toujours visible. Sur grand écran, la ligne s'écarte des boutons de zoom du bas.

## Questions en suspens

Quand tu touches un système, la feuille « Voyager vers … » monte du bas : la ligne reste-t-elle visible au-dessus d'elle, ou la feuille la recouvre (elle a déjà sa croix pour fermer) ?
