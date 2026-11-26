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

Précision (26/11) : « une fois une flotte créé et un ensemble de vaisseau regrouper, les actions sont pour la flotte ». Compris : les boutons d'action sont sur le panneau de chaque flotte ; les vaisseaux hors flotte ont les leurs sur leur ligne.

Point que je dois te signaler avant de coder (vérifié dans `Vaisseau.class.php` et `model/deplacer.php`) :
- « Recherche de système » ne fonctionne que pour les vaisseaux de reconnaissance HORS flotte (`explorer` filtre `id_flotte IS NULL`), et une flotte ne peut contenir que frégates, barges et capitaux (`typesDeFlotte`). Une flotte ne peut donc pas faire de recherche de système aujourd'hui.
- Le panneau « Donner un ordre » sert aussi aux troupes et chasseurs embarqués, qui partent avec leur porteur.

## Questions en suspens

1. Une flotte n'a donc que l'action « Reconnaissance de planète » (carte, choix du système puis de la planète) ? Ou veux-tu que des vaisseaux de reconnaissance puissent entrer dans une flotte, pour que la flotte fasse aussi la « Recherche de système » (nouvelle règle) ?
2. Les vaisseaux hors flotte (frégate, barge, capital, troupes, chasseurs) : sur leur ligne, seulement l'action « Reconnaissance de planète » (carte) ?
3. Combien de reconnaissances partent par « Recherche de système » : une seule par clic, ou tu choisis un nombre ?
