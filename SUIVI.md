# SUIVI

## À faire

### 1. Revoir toute la partie tuto : flèche et menus (27/11)

Ta phrase : « revoi toute la partie tuto, des fois la fleche qui pointe ne disparait pas quand on clique sur ce qui est pointé, et parfois quand faut changer d'ecran sa montre pas le bon menu etc.. ».

Lu (rien codé) : j'ai relu `js/tuto.js` et `Tuto::route` ; défauts trouvés.

Flèche qui ne disparaît pas :
- Elle n'est retirée qu'au rechargement de la page (`afficher()` sur `page-rafraichie`), donc elle reste 1 à 3 secondes après le clic, et indéfiniment si l'action n'avance pas l'étape.
- Les boutons qui ouvrent un panneau (embarquer, renommer) ou changent de mode (créer une flotte) ne rechargent rien : la flèche reste sur le bouton déjà cliqué, et le vrai bouton d'action du panneau n'est pas marqué.
- Quand la cible se cache (créer une flotte masque son bouton), `placer()` mesure un rectangle vide et la flèche saute dans un coin de l'écran.

Mauvais menu ou rien :
- Si tu es sur la bonne page mais pas sur le bon objet (la fiche d'une autre planète, un autre vaisseau), la cible est absente, il n'y a aucune flèche, ni bouton ni menu.
- Étape 8 (assaut) : la flèche dit « menu Carte » même quand tu es déjà dans la carte ou sur une fiche de système (sous-pages de la carte), puis plus rien pour aller à la fiche de la planète pirate.
- Étape 7 (embarquer) : elle pointe le premier bouton « embarquer » trouvé, pas forcément la barge ou la frégate voulue.
- Étape 11 (assassin) : la flèche « voyager » du transport apparaît avant que l'assassin soit à bord.

Ce que je comprends : je fais disparaître la flèche dès le clic sur la cible, elle suit ensuite la suite de l'action (panneau ouvert, bouton de validation, mode création), elle ne saute plus quand la cible est cachée, elle retombe sur l'entrée de menu quand la cible n'est pas sur la page, les sous-pages comptent comme leur menu parent, et chaque étape est vérifiée par un script qui parcourt les 16 étapes sur toutes les pages et contrôle que la flèche existe et vise le bon élément ou le bon menu.

## Questions en suspens

1. Flèche retirée dès le clic, puis réaffichée sur la cible suivante si l'étape n'est pas finie. Je recommande cela.
2. Je corrige aussi l'étape 8 (carte, fiche de système, fiche de la planète pirate) et l'étape 7 (la bonne barge ou frégate). Je recommande oui.
3. Je livre le script de contrôle des 16 étapes et je te donne son tableau de résultats. Je recommande oui.
