# SUIVI

## À faire

### 1. Tuto : l'étape Restrictor doit viser la planète 3, pas la 2 (27/11)

Ta phrase : « dans le tuto l'attaque par interdictro sa doit etre pour la planete 3, la 2 est deja clean via le tuto des chasseurs ».

Lu (rien codé) : le système voisin du tuto a au moins 3 planètes. La planète 1 est la cible de l'assaut (`planeteCible`). La « planète 2 » (`planeteDeux` : la première planète libre du système hors cible) sert à l'analyse, à l'étape des chasseurs (dégager l'orbite), à l'assassinat du gouverneur, puis à l'étape Restrictor : le tuto y pose alors 5 chasseurs pirates (`lancerFlotteReduite`), et la route, le texte et l'objectif de l'étape visent cette même planète, déjà nettoyée (orbite vidée, gouverneur éliminé, bouclier saboté).

Ce que je comprends : à l'étape Restrictor, la flotte pirate réduite (5 chasseurs) est posée sur une troisième planète du système voisin (la suivante libre après la cible et la planète 2), et c'est elle que la flotte avec Restrictor doit attaquer : texte, flèche de la carte (système, planète, « Voyager ici »), destination autorisée par le tuto et objectif de l'étape suivent cette planète 3. La planète 2 reste nettoyée et n'est plus touchée à cette étape.

### 2. Crédits attribués chaque minute, affichage arrondi (27/11)

Ta phrase : « pour les crédit heure, attribue les toutes les minutes, si c un floatant, ne l'affiche pas, apr ex pour un gain de 60ch, tu fait gagner +1 chaque minture, pour 65ch.. +1,xxx mais le gain des xxx n'arrive que pour un chfiffre rond, , bref visuellement tu arrondi ».

Lu (rien codé) : aujourd'hui les crédits sont versés une fois par heure pleine (`Joueur::updateCredits` : heures écoulées × revenu net en cr/h), au chargement d'une page. Le solde est un entier en base, sans reste. Le HUD ne se rafraîchit pas tout seul.

Ce que je comprends : le revenu est versé à la minute, à raison de revenu ÷ 60 par minute (60 cr/h = +1 par minute ; 65 cr/h = +1,083 par minute). Le solde affiché reste un entier, jamais de virgule. La fraction s'accumule dans un reste enregistré en base et ne devient un crédit entier que lorsqu'elle atteint un chiffre rond (à 65 cr/h : +1 chaque minute, et +2 environ une minute sur douze). Le total sur une heure reste exactement le revenu en cr/h ; l'affichage « +65 cr/h » ne change pas ; un revenu négatif suit la même règle.

## Questions en suspens

1. État de la planète 3 à cette étape : camp Pirates, sans gouverneur, sans bouclier ni canon, avec uniquement les 5 chasseurs (mêmes conditions que la planète 2 avait à ce moment-là, pour que le combat de fuite et le Restrictor se jouent à l'identique). Je recommande cela.
2. Choix de la planète 3 : la planète libre suivante par numéro, hors cible et planète 2 (chaque système voisin en a au moins 3). Je recommande cela.
3. Joueurs déjà arrivés à l'étape Restrictor, dont la flotte pirate est déjà sur la planète 2 : on les laisse tels quels (étape terminée avec l'ancienne cible), seuls les nouveaux joueurs reçoivent la planète 3. Je recommande cela.

4. Crédits : pour que le solde monte vraiment chaque minute à l'écran, je recommande que le HUD fasse tourner le même calcul dans le navigateur, resynchronisé à chaque page. Sinon, il ne bouge qu'à chaque chargement de page. Laquelle ?
5. Crédits : le reste fractionnaire est enregistré dans une nouvelle colonne du joueur (migration). Je recommande cela.

À ne coder qu'à ton « go ».
