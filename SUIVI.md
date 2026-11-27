# SUIVI

## À faire

### 1. Crédits attribués chaque minute, affichage arrondi (27/11)

Ta phrase : « pour les crédit heure, attribue les toutes les minutes, si c un floatant, ne l'affiche pas, apr ex pour un gain de 60ch, tu fait gagner +1 chaque minture, pour 65ch.. +1,xxx mais le gain des xxx n'arrive que pour un chfiffre rond, , bref visuellement tu arrondi ».

Lu (rien codé) : aujourd'hui les crédits sont versés une fois par heure pleine (`Joueur::updateCredits` : nombre d'heures écoulées × revenu net en cr/h), au moment où le joueur charge une page. Le solde est un entier en base, sans reste. Le HUD ne se rafraîchit pas tout seul : le solde ne change qu'au chargement d'une page.

Ce que je comprends : le revenu en cr/h est versé à la minute, à raison de revenu ÷ 60 par minute (60 cr/h = +1 par minute ; 65 cr/h = +1,083 par minute). Le solde affiché reste un entier, jamais de virgule. La fraction n'est pas perdue : elle s'accumule dans un reste (en base) et ne devient un crédit entier que lorsqu'elle atteint un chiffre rond, par exemple à 65 cr/h : +1 chaque minute, et +2 une minute sur douze environ. Le total sur une heure reste exactement le revenu en cr/h. L'affichage du revenu (« +65 cr/h ») ne change pas. Un revenu négatif suit la même règle.

## Questions en suspens

1. Le versement se fait à chaque chargement de page (comme aujourd'hui, mais par minutes écoulées), donc rien ne bouge tant que le joueur ne change pas de page. Pour que le solde monte réellement chaque minute à l'écran, je recommande que le HUD fasse tourner le même calcul côté navigateur : +1 visible à chaque minute, resynchronisé à chaque page. Sinon le solde ne bouge qu'à chaque chargement. Laquelle ?
2. Le reste fractionnaire est enregistré dans une nouvelle colonne du joueur (migration) pour ne rien perdre entre deux visites. Je recommande cela.

À ne coder qu'à ton « go ».
