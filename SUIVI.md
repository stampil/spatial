# SUIVI

## À faire

### 1. Flottes : « Créer une flotte » et « Annuler » en simples liens (26/11)

Ta phrase : « creer une flotte devient juste un hyperlien, annuler aussi ».

Lu (rien codé) : en haut à droite de l'encart, « Créer une flotte » est un vrai bouton (`md3-button` tonal), et l'annulation est une croix « × » (`model/listeVaisseau.php`, `css/jeu.css`). Même style de lien texte souligné que « Annuler » sur la carte (`.carte__lien`).

Ce que je comprends : « Créer une flotte » devient un lien texte souligné (sans fond de bouton), et la croix devient un lien texte « Annuler », à côté.

## Questions en suspens

Quand 2 vaisseaux sont cochés, le lien « Créer une flotte » devient « Enregistrer » : en lien texte lui aussi (cohérent), ou « Enregistrer » reste un vrai bouton puisqu'il valide ?
