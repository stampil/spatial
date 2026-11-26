# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Flottes : les 2 actions de reconnaissance directement sur la page, plus de bouton « Donner un ordre » (26/11)

Tes phrases : « flottes, donner un ordre ne raffiche pas le vaisseau ou sa flotte, sa liste juste els ordre possible, et la tu dnne 2 actions possible : reconnaissance de planete ou recherche de systeme attendtion au nom et la longueur c principalement sur mobile que sa tourne », puis « les 2 action doive entre au niveau de la page flotte, donc y a plus de btn donner un ordre, mais direct les 2 actions que je t'ai dit. pour recherche de systeme sa lance la mecanique sans afficher la vue carte, pour la reco planete, alors oui sa fait ouvrir la carte et on doit choisir le systeme et la planete où aller ».

Ce que j'ai compris (rien codé) :
- Sur la page Flottes, le panneau « Donner un ordre » (nombres par type + cases de flotte + « Choisir la destination sur la carte ») disparaît.
- À la place, 2 boutons directs :
  - « Recherche de système » : lance l'exploration tout de suite (`?p=deplacer`, destination `explorer`), sans ouvrir la carte, puis retour sur Flottes.
  - « Reconnaissance de planète » : ouvre la carte, tu choisis le système puis la planète.
- Textes courts, pensés mobile.

Précisions (26/11) :
- « une fois une flotte créé et un ensemble de vaisseau regrouper, les actions sont pour la flotte ».
- « Q1 pour tout, y a plus de donner u nordre putain » : plus aucun « Donner un ordre », pour aucun type d'unité ; les actions directes le remplacent partout.
- « Q2 jamais de selecteur, les vaisseaux sont atomique do'ou le regroupement par flotte si on veux factiriser le nb pour une action » : aucun champ de nombre dans les ordres. Un clic = un vaisseau ; pour en envoyer plusieurs d'un coup, on les regroupe en flotte.
- « 3 a la place de donner un ordre putain » : les boutons vont à l'emplacement de l'ancien panneau, dans la section de chaque planète.
- « attention au nom et la longueur c principalement sur mobile que sa tourne » : noms et textes courts.

Ce que je comprends du résultat (rien codé) :
- Section de planète, une ligne par type de vaisseau (ex. « Reconnaissance × 3 ») avec ses boutons ; chaque clic agit sur UN vaisseau de la ligne. Une flotte a sa ligne et ses boutons, qui agissent sur toute la flotte.
- Reconnaissance : « Recherche de système » (part tout de suite, sans carte) et « Reconnaissance de planète » (ouvre la carte : système puis planète).
- Frégate, barge, capital, flotte, troupe, chasseur : l'envoi vers une planète choisie sur la carte.

Points à trancher avant de coder (vérifiés dans `Vaisseau.class.php`) :
- Les troupes ne voyagent jamais seules et les chasseurs ne changent de système qu'embarqués (frégate : 10 troupes, 3 chasseurs ; barge : 10 troupes ; capital : 20 troupes, 6 chasseurs). Sans champ de nombre, il faut une règle pour embarquer.
- Les persos du transport personnel partent déjà tout seuls avec le transport : rien à changer.

## Questions en suspens

1. Embarquement sans sélecteur : quand j'envoie une frégate, une barge ou un capital (ou une flotte), les troupes et chasseurs présents sur la planète montent automatiquement, jusqu'à la capacité du vaisseau ? Les troupes et chasseurs n'ont alors pas de bouton d'envoi propre pour changer de système (un chasseur peut encore bouger dans son système).
2. Libellés courts : pour les vaisseaux autres que la reconnaissance (et les flottes), un seul bouton « Envoyer » (carte) ; pour la reconnaissance « Chercher un système » et « Reconnaître une planète » ?
3. Une flotte ne peut toujours pas contenir de reconnaissance, donc pas de « Recherche de système » pour une flotte : confirmes-tu ?
