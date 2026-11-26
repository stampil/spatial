# SUIVI

## À faire

### 1. Tuto : dire d'aller dans le menu, avec une grosse flèche animée (26/11)

Ta phrase : « ne dit pas de cliquer sur l'icone perso, mais d'aller dans le menu perso, et tu fait une grosse flesh animé qui pointe où faut aller dans les tuto ».

Lu (rien codé) : l'étape 0 (`model/tuto_0.php`) dit « Touchez l'icône des persos » et affiche un bouton icône ; le menu est maintenant visible, donc on peut y renvoyer. Les autres étapes (`inc/class/Tuto.class.php`) disent « Ouvrez Flottes », « sur la fiche de… » sans rien montrer.

Ce que je comprends :
- Étape 0 : le texte dit « Allez dans le menu Persos » et le bouton icône disparaît ; une grosse flèche animée pointe l'entrée « Persos » du menu (barre du haut sur PC, barre du bas sur téléphone).
- Chaque étape du tuto a une cible : tant que le joueur n'est pas sur la bonne page, la flèche pointe l'entrée du menu à ouvrir (Colonies, Flottes, Persos, Carte) ; une fois sur la bonne page, elle pointe le bouton icône à toucher (Chercher, Embarquer, Voyager, Attaquer, Débarquer, Construire, Produire…). Elle disparaît quand l'étape est validée.
- La flèche est grosse (64 px), lavande, elle rebondit vers sa cible ; sans animation si le système demande moins de mouvement.

## Questions en suspens

1. Toutes les étapes du tuto reçoivent leur flèche (menu puis bouton), pas seulement l'étape 0 ? Je recommande toutes.
2. Les textes des étapes disent « allez dans le menu X » au lieu de « ouvrez X » ou « touchez l'icône », et gardent l'icône du bouton en ligne quand on cite une action. Je recommande oui.
