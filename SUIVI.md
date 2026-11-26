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

Précisions du 26/11 : « Go, recommandé », puis « non reconquise alors » (en réponse à ma question sur « conquise »).

Ce que je comprends de ta précision : pas d'événement quand un autre joueur prend une de tes planètes (donc pas besoin de prévenir un joueur absent : tout se passe dans ta propre session). Et l'événement de conquête par la force s'appelle « reconquise », pas « conquise ».

## Questions en suspens

1. « Reconquise » : je le comprends comme la planète qui était à toi (révoltée ou perdue) et que tu reprends par la force. Pour que le jeu sache qu'elle était à toi, il faut mémoriser le dernier gouverneur d'une planète devenue neutre (une colonne de plus, migration 025). Une conquête par la force d'une planète qui n'a jamais été à toi (un pirate, une planète neutre) n'a alors pas d'événement illustré. C'est bien cela ? Sinon, dis-moi : « reconquise » désigne-t-il toute conquête par la force ?
2. Illustrations : 3 images à générer avec toi (rejoint, révolte, reconquise), les mêmes pour tous les clans, le clan étant rappelé par son emblème et son nom. Je recommande 3 images.
3. « Se révolte » : la fenêtre s'ouvre à ta prochaine page chargée après le passage à 100 %. Je recommande oui.
