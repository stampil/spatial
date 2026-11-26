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

## Questions en suspens

1. Bouton : icône seule avec infobulle / nom accessible, ou icône + un seul mot court (« Attaquer », « Voyager »…) ? Sur mobile, une icône seule est plus compacte mais moins claire.
2. Les infos qui sont dans le libellé aujourd'hui (nombre de vaisseaux, durée, chance de réussite) : en petit texte à côté du bouton ?
3. Périmètre : on commence par les actions de combat (attaquer avec vaisseaux, attaquer avec flotte, attaquer avec chasseurs, débarquer les troupes) et la page Flottes (voyager, chercher, reconnaître, embarquer, débarquer, renommer, dissoudre) ; le reste (production, construction, rôles) ensuite ?
4. « Attaque de chasseurs » : une action propre aux chasseurs (distincte de l'attaque des vaisseaux de combat), ou juste l'icône de l'attaque quand les chasseurs sont là ?
