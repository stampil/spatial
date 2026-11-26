# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Ordre sur la carte : rappeler le vaisseau ou la flotte, nommer les 2 actions, penser mobile (26/11)

Ta phrase : « flottes, donner un ordre ne raffiche pas le vaisseau ou sa flotte, sa liste juste els ordre possible, et la tu dnne 2 actions possible : reconnaissance de planete ou recherche de systeme attendtion au nom et la longueur c principalement sur mobile que sa tourne ».

Ce que j'ai compris (rien codé) :
- Depuis Flottes, « Donner un ordre » > « Choisir la destination sur la carte » ouvre la carte avec un bandeau qui dit seulement « Touchez le système de destination, ou Explorer : … » (`model/carte.php`, `js/carte.js`). Il ne rappelle pas ce qui part (vaisseaux, nombres, flotte).
- Avec une reconnaissance, 2 actions existent : toucher un système connu (reconnaître une planète) ou le bouton « Explorer » (chercher un nouveau système). Le libellé actuel ne les distingue pas clairement.
- Noms et textes courts, pensés pour le mobile.

Proposition (rien codé) : dans le bandeau, une ligne de rappel compacte (icône + nombre par type de vaisseau, nom de la flotte), puis deux boutons aux libellés courts et distincts.

## Questions en suspens

1. Le rappel doit-il être sur la carte (bandeau, après « Choisir la destination »), ou sur la page Flottes elle-même (dans « Donner un ordre ») ?
2. Libellés des 2 actions, courts : « Reconnaître » (toucher un système) et « Chercher » (bouton) te vont-ils, ou tu préfères d'autres mots ?
3. Faut-il masquer l'action qui ne s'applique pas (ex. sans reconnaissance, pas de « Chercher ») ?
