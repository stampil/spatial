
# Installation

- PHP 8.2 minimum, avec l'extension `pdo_mysql`.
- Créer la base `systeme` : le jeu y installe lui-même ses tables et peuple l'univers à la première visite (`database/installation.sql`). On peut aussi y importer `database/systeme.sql`, avec ses données d'exemple.
- Sur une base déjà existante, importer `database/migration_revival.sql` (nouvelles colonnes et tables).
- Ensuite, chaque évolution de la base est un fichier numéroté de `database/migrations/` : le jeu les applique seul à la première visite qui suit (table `schema_migrations`). Un changement de schéma s'écrit donc dans une migration, et dans `database/systeme.sql` avec la ligne de `schema_migrations`.
- Copier `inc/config.example.php` en `inc/config.php` et y renseigner les identifiants de la base.
- Sur une base importée à la main, peupler l'univers une seule fois (systèmes d'Empire, de Pirates, disputés et sans camp) : `php database/peupler.php`.
- Lancer en local depuis la racine du projet : `php -S localhost:8000`.

# Elements de gameplay
## mot d'ordre simplification pour faire un noyau dur et fonctionnel

faction empire : ressources unités coutes moins cher, mais tjrs visible sur la carte
faction rebellion: invisible si pas de ouverte
faction pirate : peut se battre entre eux, chaque combat doit etre validé, pas auto, invisible si pas decouvert.
peut detourner des ressource sans conquerir les planetes

3 type de vaisseaux de combat :
 
chasseur  / fregate / vaisseaux capitale :

lors des combats : 1er phase les chasseurs : vainqueur octroi bonus au fregate : 

2ieme phase confrontation des fregates : vaiqueur octroi bonus au capitaux

3ieme phase confrontation des vaiss capitaux : designation vainqueur avec flotte restante.

regroupement possible en creation dynamique de flotte : avec n chasseur; n fregate et n capital

1 vaisseau de reconnaissance : permet de trouver de nouveau systeme, et d'avoir les caract de planete inconnue

2 type de vaisseaux de transport : 
vaisseaux de transport personnel : peu de personne, va vite.
barge de debarquement : bcp de personne, lent, et si detruit grosse perte.

planete a peu de slot, on peut y mettre , genere un revenu minimal:

pas d'upgrade d'usines, toutes dispo, juste un choix de ressource via slot limité :
 
- usine de vaisseau leger : chasseur ou reco ou transport perso

- usine de vaisseau moyen : fregate ou barge

- usine de vaissau lourd : vaisseau capitale

- ferme pour booster revenu

- académie : forme Hero + troupe d'assault + diplomate + assassin


Prise de planete par troupe assaut une fois defense planetaire detruite ( cbt vaisseau vs bouclier et canon planetaire + cbt troupe au sol )
rapide mais perte ressource de combat, et planete revolté

Prise de planete par diplomatie : + long et risque diplomate assassiné.

reduction revolte par diplomatie, si revolte et aucune troupe planete prend son independance= aucun revenu.

chaque nouveau joueur fait créer de maniere auto un nouveau systeme où il est seul. tuto accompagne jusqu'a la prise d'une autre planete dans le systeme.

fin tuto = systeme visible par les reco des autres joueurs.

Heros sont une sorte de boost pour les combats ou la diplomatie

but du jeu : avoir la plus grosse flotte et conquerir le + de planete


source de revenue : 1 ressource 'argent' : generer par planete et recup d'epave auto si victoire fin de combat

toute les constructions ont un coup de maintenance qui se deduit du revenue.

revenu peut etre negatif mais plus de construction possible.

