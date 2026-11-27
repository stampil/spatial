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

### 2. Carte : vue des planètes d'un système, avec positions et temps de trajet (27/11)

Ta phrase : « dans la vue carte, quand on clique sur un systeme, tu reprend la vue de la carte mais au lieu d'afficher les galaxie tu affiche les planetes du systeme, les positions x,y sont donc noté et depende aussi du temps pour ls rejoindre. revoi tes calcul mais passer d'une planete a une autre dans le mme systeme doit tjrs etre moins long que passer d'un systeme a un autre ».

Lu (rien codé) : aujourd'hui, toucher un système sur la carte ouvre une page de fiche de système (\`afficheSysteme\`), pas la carte. Les systèmes ont des coordonnées x,y (1328 systèmes, de −1652 à 2608 et de −1540 à 2525) ; les planètes n'en ont pas. La durée d'un trajet (\`Vaisseau::dureeEntre\`) est : distance entre systèmes × 1 s, et fixe à 60 s dans un même système, quelle que soit la paire de planètes. La distance minimale entre deux systèmes est 80 ; l'ordre « même système plus court » tient donc aujourd'hui (60 < 80), mais par hasard : 60 est une constante sans lien avec la géométrie.

Ce que je comprends :
- Toucher un système sur la carte rouvre la même carte (même zoom animé, mêmes ordres de déplacement) mais avec les planètes du système à la place des systèmes : chaque planète a une position x,y dans son système, affichée comme un nœud.
- La durée entre deux planètes d'un même système dépend de leur distance dans le système, avec un plancher et un plafond choisis pour qu'elle reste toujours strictement inférieure au plus court trajet entre deux systèmes (80 s de base), quelle que soit la réduction, la vitesse du vaisseau ou la division des durées du tuto, qui s'appliquent pareil aux deux.
- Un bouton pour revenir à la vue des systèmes.

## Questions en suspens

1. Positions : je les enregistre en colonnes (position x,y de chaque planète dans son système), remplies une fois par une migration à partir d'une disposition en orbites régulière et stable, plutôt que recalculées à chaque affichage. Je recommande cela.
2. Durées : de 20 s (planètes voisines) à 60 s (planètes opposées) dans un système, contre 80 s au minimum entre systèmes ; un contrôle automatique vérifie l'ordre pour tous les systèmes. Je recommande ces bornes.
3. La page de fiche de système actuelle reste accessible depuis la feuille d'un système sur la carte (nom, camp, planètes). Je recommande de la garder.
