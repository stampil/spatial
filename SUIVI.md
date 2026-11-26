# SUIVI

## À faire

### 1. Événements illustrés : planète qui rejoint, se révolte, est conquise (26/11)

Ta phrase : « fait un evenement et illustration quand une planete rejoint notre cause ( ntimporte quel clan ) et inverse quand elle se revolte, et aussi quand elle a été conquise ».

Lu (rien codé) : ces trois moments existent déjà, mais ne donnent qu'une ligne de journal et un message de 8 secondes, sans image.
- Rejoint par la diplomatie : `Perso::finishDiplomatie` appelle `Planete::gouverner` (`inc/class/Perso.class.php`).
- Se révolte : `Planete` repasse neutre à 100 % de révolte, « est devenue indépendante » (`inc/class/Planete.class.php`).
- Conquise par la force : `Planete::capturer` après un débarquement réussi (`inc/class/Combat.class.php`, avec le rapport « Débarquement »).
La fenêtre d'information existe (`Tools::addInfo`, `inc/info.php`), mais sans illustration ; les rapports de combat ont, eux, une illustration.

Ce que je comprends : chacun des trois moments ouvre une fenêtre avec une grande illustration, un titre, deux lignes de texte et l'emblème et le nom du clan concerné (Empire, Rébellion ou Pirates, quel que soit le clan du joueur), en plus de la ligne de journal.

Précisions du 26/11 : « Go, recommandé », puis « non reconquise alors », puis « mais l'illistration devrais etre similaire, une planete conquise par la force ».

Ce que je comprends : pas d'événement quand un autre joueur te prend une planète. Un seul événement de conquête par la force, « conquise » ou « reconquise » avec la même illustration : une planète conquise par la force. Je ne mémorise donc pas l'ancien gouverneur (pas de colonne ni de migration) : toute conquête par la force d'une planète donne l'événement, avec le même texte.

## Questions en suspens

1. Illustrations : 3 images à générer avec toi (rejoint, révolte, conquise par la force), les mêmes pour tous les clans, le clan étant rappelé par son emblème et son nom dans la fenêtre. Je recommande cela.
2. « Se révolte » : « Se revolte c a partir de 51 hein » (26/11). Dans le jeu, une planète devient indépendante à 100 % de révolte (\`Planete::MAX_REVOLTE\`) ; une planète capturée démarre à 50 % (\`CAPTURE_REVOLTE\`) et l'alerte « Révolte élevée » apparaît à 70 %. Deux lectures :
   - A : la fenêtre « se révolte » s'ouvre dès que la révolte dépasse 50 % (51 %), la planète restant à toi jusqu'à 100 %. Un seul passage de seuil, une fois, à la prochaine page chargée ; la planète occupée par une garnison ne progresse pas, donc pas de fenêtre tant que la garnison tient. Je recommande cette lecture : aucune règle de jeu ne change.
   - B : la planète passe indépendante dès 51 % au lieu de 100 %. Cela change la règle : une planète capturée (50 %) quitterait ton camp 1 h après la fin de l'occupation. Je ne le fais pas sans ton accord.
   Quelle lecture, A ou B ? Dans les deux cas, la fenêtre d'indépendance à 100 % (A) ou à 51 % (B) montre l'illustration « révolte ».
