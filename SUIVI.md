# SUIVI

## À faire

### 1. Attaque de planète : qui compte dans l'attaque (26/11)

Ta phrase : « dans l'attaque de planette, si les vaisseau ne sont pas debarquer y a pas d'action attaquer avec vaisseaux de combat, par contre une attaque avec flotte si les vaisseaux sont embarquer ils sont comtabilisé dans l'attaque, donc dans le tuto dit le pas besoin de sortir les chasseaur des fregate. »

Lu (rien codé) : c'est aujourd'hui l'inverse. `Combat::attaquer` (`inc/class/Combat.class.php`) compte, pour « Attaquer avec N vaisseaux de combat » (hors flotte), TOUS les vaisseaux de combat présents, y compris les chasseurs embarqués sur une frégate ; et pour une attaque « avec la flotte » il ne compte que les vaisseaux de la flotte, pas les chasseurs embarqués dessus (ils ont `id_flotte` vide). Le bouton `model/affichePlanete.php` suit la même logique.

Ce que je comprends :
- « Attaquer avec N vaisseaux de combat » (hors flotte) ne compte que les vaisseaux de combat non embarqués ; sans aucun vaisseau de combat débarqué, l'action n'apparaît pas.
- « Attaquer avec <flotte> » compte les vaisseaux de la flotte ET les chasseurs embarqués sur ses vaisseaux ; les pertes s'appliquent comme avant (par type). Le nombre affiché dans le libellé les inclut.
- Tutoriel, étape « Prendre et tenir la planète pirate » : le texte dit d'attaquer avec la flotte, sans avoir à sortir les chasseurs de la frégate.

### 2. Boutons d'action : des icônes au lieu de textes longs (26/11)

Ta phrase : « fait des icones d'attaques de flottes, de chasseur, la les btn ont des text trops long, on va faire des icones pour chaque action btn ».

Lu (rien codé) : les boutons d'action portent des phrases longues (« Attaquer avec Flotte 1 (2 vaisseaux) », « Débarquer 3 troupes des barges », « Faire de la diplomatie sur … (durée, chance) »…). Il y en a une vingtaine : attaquer (vaisseaux, flotte), débarquer les troupes, voyager, chercher un système, reconnaître une planète, embarquer, débarquer (vaisseau), renommer, dissoudre, produire, construire, démolir, recruter, diplomatie, apaiser, assassiner, monter / descendre du transport, combattre, laisser faire. Les 24 icônes duotones déjà générées couvrent des thèmes (carte, bouclier, canon, poing…), pas ces actions.

Ce que je comprends : chaque bouton d'action devient un bouton à icône (style duotone, générées comme les 24 précédentes, prompts un par un), avec le détail (nombre, durée, chance) sorti du libellé.

### 3. Flottes IA stationnées : elles participent à la défense (26/11)

Ta phrase : « il faut modeliser aussi des flottes IA qui stationner sur une planete participe a la defense ».

Lu (rien codé) : une planète tenue par un camp IA (pirates, empire, rébellion) n'a aujourd'hui que 3 valeurs de défense : bouclier, canon, troupes (garnison) ; elles se reconstituent de x % par heure. Elle n'a AUCUN vaisseau : les raids de l'IA (`Ia::raid`, `Menace`) sont abstraits (une « puissance » tirée de la taille de la planète source). Dans `Combat::attaquer`, seuls bouclier et canon de la planète encaissent et tirent.

Ce que je comprends : une planète IA peut avoir une flotte stationnée (des chasseurs, frégates, capitaux comme les tiens). Quand tu l'attaques, cette flotte défend avec le bouclier et le canon : sa force s'ajoute à la défense, elle subit des dégâts, peut être détruite, et tes pertes en tiennent compte.

Précision (26/11) : « les flottes joueur advrse participe aussi bien sur, mais tuto c ia ». Compris :
- Les flottes d'un joueur adverse stationnées sur la planète attaquée défendent aussi (tous ses vaisseaux de combat présents au repos, en flotte, hors flotte ou chasseurs embarqués). Aujourd'hui `Combat::attaquer` ne regarde que le bouclier et le canon de la planète : les vaisseaux du défenseur ne tirent pas. Leurs pertes s'appliquent comme les tiennes, et le défenseur en est averti dans son journal.
- Le tutoriel utilise une planète IA (pirate) : le modèle de flotte IA s'y applique donc aussi.

Proposition (à valider) : un petit stock de vaisseaux par planète IA (type + nombre, dans une table dédiée, pas des vaisseaux de joueur), dimensionné par la taille de la planète et le camp ; il apparaît à la génération de l'univers, se reconstitue lentement comme la défense, et se voit sur la fiche de la planète (« Flotte pirate stationnée : N vaisseaux »). Dans le combat, les vaisseaux défenseurs (IA ou joueur adverse) tirent dans les mêmes phases que les tiens et absorbent des dégâts avant le canon.

### 4. Combat automatique à l'arrivée et popin « Résoudre » (26/11)

Ta phrase : « quand on arrive sur une planete avec flotte hostile sa declenche immediatement le combat, on a un popin illustrer combat spatial avec un btn résoudre, et on voit les barre de défenses se reduire en fct comme pour un combat de planete. idem pour le combat terrestre au debarquement, popin illuster puis resoudre pour voir l'animatiob ».

Lu (rien codé) : aujourd'hui un combat ne part que sur ton clic « Attaquer » (page de la planète), et l'animation des barres (`inc/rapport.js`, `inc/rapport.php`) se joue toute seule avec « Passer ». Le débarquement ne donne qu'un message texte, sans animation. L'arrivée d'une flotte ne déclenche rien.

Ce que je comprends :
- Quand tes vaisseaux de combat (flotte ou seuls) arrivent sur une planète où il y a une flotte hostile (flotte IA stationnée ou flotte d'un joueur adverse), le combat spatial se résout tout de suite côté serveur.
- Au prochain affichage, une fenêtre illustrée « Combat spatial » s'ouvre avec un bouton « Résoudre » ; au clic, les barres de force des deux flottes se réduisent phase par phase, comme pour un combat de planète.
- Même principe pour le débarquement : une fenêtre illustrée « Combat terrestre » (troupes contre garnison), puis « Résoudre » joue l'animation. Le résultat est déjà décidé : le bouton ne fait que révéler la bataille.

## Questions en suspens

Point 4 (combat à l'arrivée) :
9. Le combat à l'arrivée ne se déclenche que si le groupe qui arrive contient des vaisseaux de combat (chasseurs, frégates, capitaux) ; les vaisseaux non armés (reconnaissance, transports) arrivent en paix. D'accord ?
10. Illustrations : le combat spatial réutilise l'image `combat.jpg` déjà là ; pour le combat terrestre, je te donne un prompt Gemini pour une nouvelle image. D'accord ?
11. Après « Résoudre », on garde « Passer » pour sauter l'animation ?
