# SUIVI

## À faire

### 1. Icônes embarquer et débarquer plus lisibles (27/11)

Ta phrase : « l'iconne pour faire embarquer ou desembarquer est trop petite donc on voit aps ce qu sa fait, lasisse la taille mais rajoute un gros + en bas a droite dans l'icone et un gros moins pour le desembarquement ».

Ce que je comprends : on garde la taille actuelle des boutons ; les icônes « embarquer » reçoivent un gros « + » blanc en bas à droite, dans le bouton, et les icônes « débarquer » un gros « − ». Partout où elles servent : liste des vaisseaux (bouton d'ouverture du panneau, bouton de validation, bouton débarquer), fiche du perso (monter dans un transport, descendre du transport).

### 2. Brouillard de guerre sur les planètes non explorées (27/11)

Ta phrase : « les planetes non exploré tu leur met un voile noire avec opacité 75% une sorte de brouillardde guerre visuel ».

Ce que je comprends : dans la vue des planètes d'un système sur la carte, chaque planète que le joueur n'a pas explorée (aucun relevé de reconnaissance : \`Analyse::releve\` vide) est recouverte d'un voile noir à 75 % d'opacité, comme un brouillard de guerre ; elle reste cliquable (feuille d'infos visibles, choix de destination). Une planète explorée s'affiche normalement.

## Questions en suspens

0. Go pour le « + » sur embarquer et le « − » sur débarquer, dans le bouton ?

1. « Non explorée » = pas de relevé de reconnaissance pour ce joueur (même règle que « ? » dans les pastilles de la fiche de système) ; le voile couvre le disque de la planète et son nom, uniquement dans la vue des planètes du système, pas dans la galaxie. Je recommande cela. À ne coder qu'à ton « go ».
