# SUIVI

## À faire

### 1. Carte : vue des planètes d'un système, avec positions, temps de trajet et choix de destination (27/11)

Ta phrase : « dans la vue carte, quand on clique sur un systeme, tu reprend la vue de la carte mais au lieu d'afficher les galaxie tu affiche les planetes du systeme, les positions x,y sont donc noté et depende aussi du temps pour ls rejoindre. revoi tes calcul mais passer d'une planete a une autre dans le mme systeme doit tjrs etre moins long que passer d'un systeme a un autre ».

Lu (rien codé) : aujourd'hui, toucher un système sur la carte ouvre une page de fiche de système (\`afficheSysteme\`), pas la carte. Les systèmes ont des coordonnées x,y (1328 systèmes, de −1652 à 2608 et de −1540 à 2525) ; les planètes n'en ont pas. La durée d'un trajet (\`Vaisseau::dureeEntre\`) est : distance entre systèmes × 1 s, et fixe à 60 s dans un même système, quelle que soit la paire de planètes. La distance minimale entre deux systèmes est 80 ; l'ordre « même système plus court » tient donc aujourd'hui (60 < 80), mais par hasard : 60 est une constante sans lien avec la géométrie.

Ce que je comprends :
- Toucher un système sur la carte rouvre la même carte (même zoom animé, mêmes ordres de déplacement) mais avec les planètes du système à la place des systèmes : chaque planète a une position x,y dans son système, affichée comme un nœud.
- La durée entre deux planètes d'un même système dépend de leur distance dans le système, avec un plancher et un plafond choisis pour qu'elle reste toujours strictement inférieure au plus court trajet entre deux systèmes (80 s de base), quelle que soit la réduction, la vitesse du vaisseau ou la division des durées du tuto, qui s'appliquent pareil aux deux.
- Un bouton pour revenir à la vue des systèmes.

Précision du 27/11 : « les planetes affiché dans l vue du systeme sont cliquable et permette de choisir la target de destination des voyages., sinon au clqi on voit les info visible ». Donc dans la vue des planètes d'un système : avec un ordre de déplacement en cours, toucher une planète la choisit comme destination ; sans ordre, toucher une planète ouvre sa feuille d'infos (ce qui est visible pour le joueur : le relevé de reconnaissance s'il existe, sinon seulement ce qu'il peut voir).

### 2. Arrivée sur une planète non reconnue et combat automatique (27/11)

Ta phrase : « quand on amene une flotte sur une planete qui n'a pas eu le vaisseau de reconnaissance, sa affcihe les info de la planete, mais la c la surprise si y a une flotte on pet se faire auto attaqué.. d'ailleur verifie le tuto mais la premiere flotte qu'on amene a la planete decouverte devrai avoir l'evenement auto d'attqauer, ce n'est plus a nous de choisir si on fait le combat spacial ».

Lu (rien codé) : à l'arrivée, \`Combat::arrivee\` lance déjà un combat spatial automatique contre les vaisseaux défenseurs (flotte pirate, flotte adverse), mais pas contre le bouclier et le canon de la planète : ceux-là ne sont engagés que par le bouton « Attaquer » (\`affichePlanete\`). Le tuto, étape 8, dit encore « attaquez avec votre flotte » avec une flèche sur ce bouton.

Ce que je comprends :
- Planète non reconnue : à l'arrivée on voit seulement les infos visibles, et si des vaisseaux défenseurs sont présents, le combat part tout seul, sans choix, en embuscade (rapport « Combat spatial » avec le bouton Résoudre).
- Tuto : l'arrivée de la première flotte sur la planète découverte déclenche le combat automatiquement ; le texte et la flèche de l'étape 8 ne parlent plus d'« attaquer » mais disent que le combat se lance à l'arrivée, puis pointent « Débarquer » quand la défense est tombée.

### 3. Icônes embarquer et débarquer plus lisibles (27/11)

Ta phrase : « l'iconne pour faire embarquer ou desembarquer est trop petite donc on voit aps ce qu sa fait, lasisse la taille mais rajoute un gros + en bas a droite dans l'icone et un gros moins pour le desembarquement ».

Ce que je comprends : on garde la taille actuelle des boutons ; les icônes « embarquer » reçoivent un gros « + » blanc en bas à droite, dans le bouton, et les icônes « débarquer » un gros « − ». Partout où elles servent : liste des vaisseaux (bouton d'ouverture du panneau, bouton de validation, bouton débarquer), fiche du perso (monter dans un transport, descendre du transport).

## Questions en suspens

0. Go pour le « + » sur embarquer et le « − » sur débarquer, dans le bouton ?

1. Les planètes du système sont des nœuds cliquables ; avec un ordre en cours, un toucher choisit la destination ; sans ordre, un toucher ouvre la feuille d'infos visibles. Je recommande cela.
2. Le combat automatique à l'arrivée engage-t-il aussi le bouclier et le canon de la planète (défense complète), ou seulement les vaisseaux comme aujourd'hui, le bouton « Attaquer » restant pour la défense planétaire ? Je recommande la défense complète dans l'arrivée : plus de choix à faire pour le combat spatial, comme tu le demandes, et plus de bouton « Attaquer ».
3. Positions des planètes en colonnes enregistrées, durées de 20 à 60 s dans un système contre 80 s minimum entre systèmes, fiche de système conservée. Je recommande ces trois choix.
