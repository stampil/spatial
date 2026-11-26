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

Point que je dois te signaler avant de coder : le panneau « Donner un ordre » sert aussi à envoyer les frégates, barges, capitaux, flottes, troupes et chasseurs embarqués. Si je le supprime sans remplaçant, plus rien ne permet de les déplacer.

## Questions en suspens

1. Les 2 actions ne concernent-elles que le vaisseau de reconnaissance ? Et comment déplace-t-on les autres vaisseaux, flottes, troupes et chasseurs : je garde « Donner un ordre » pour eux seuls, ou un bouton « Envoyer » par ligne (vaisseau ou flotte) qui ouvre la carte ?
2. Combien de reconnaissances partent par action : une seule par clic, ou tu choisis un nombre ?
3. Où placer les 2 boutons : sous la ligne des reconnaissances de chaque planète (où elles sont posées) ?
