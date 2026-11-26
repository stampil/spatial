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

## Questions en suspens

1. Illustrations : 3 images à générer avec toi (rejoint, révolte, conquise), les mêmes pour tous les clans, le clan étant rappelé par son emblème et son nom dans la fenêtre. Je recommande 3 images plutôt que 9 (une par clan).
2. « Conquise » : par la force par toi (débarquement réussi), comme je le comprends ; et aussi quand un autre joueur te prend une de tes planètes ? Je recommande de couvrir les deux, avec un texte adapté.
3. « Se révolte » : la fenêtre s'ouvre à ta prochaine page chargée après le passage à 100 %, comme les autres messages différés. Je recommande oui.
