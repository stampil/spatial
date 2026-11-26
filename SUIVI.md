# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Infos de l'explorateur : système découvert, planète analysée, signal perdu (26/11)

Ta phrase : « fait apparaitre une info quand le explorateur a decouvert un nouveau systeme, a analyser une planete où ne donne plus signe de vie ( perdu a jamais ) ».

Lu (rien codé) : ces 3 événements ont déjà un texte (`Vaisseau::resoudreExpeditions` et `resolveArrivees`), affiché comme un bandeau qui disparaît tout seul après 8 s et noté dans le journal. Un bandeau qui s'efface se rate facilement, surtout sur mobile.

Ce que je comprends : une vraie info bien visible pour chacun des 3 cas :
- « Système découvert » (retour d'une « Chercher un système » qui a trouvé quelque chose) ;
- « Planète analysée » (une reconnaissance arrive sur une planète : « Reconnaître une planète ») ;
- « Signal perdu » (l'explorateur ne donne plus signe de vie, perdu à jamais).

### 3. Curseurs de nombre : minimum 1 par défaut (26/11)

Ta phrase : « c bete en fait que le min soit a 0 pour les action de nb, met a 1 le min et c le défaut ».

Lu (rien codé) : `Vue::curseur` fixe `min="0"` pour tous les curseurs. 3 usages : production (usine, `inc/planeteGestion.php`, valeur de départ 1 si possible), création de flotte (un curseur par type : frégate, barge, capital) et embarquement (troupes, chasseurs ; valeur de départ = déjà embarqués) dans `model/listeVaisseau.php` et `inc/vaisseauNomme.php`.

Ce que je comprends : pour les actions où l'on choisit un nombre à agir, le curseur va de 1 à max et démarre à 1. Cas à ne pas casser : sans embarquer rien (0 troupe), ou une flotte sans barge, il faut pouvoir dire « zéro ».

Proposition (rien codé) : production = min 1, défaut 1 (ne rien produire = ne pas cliquer). Embarquement et création de flotte : minimum 0 conservé, car « 0 » y a un sens (débarquer, type absent de la flotte).

### 4. Assaut : seules les barges de débarquement débarquent (26/11)

Ta phrase : « pour preparer l'asseau ... faut faire produire une barge de débarquement, c'est que avec les barge que l'action debarquer s'active, et seule les trouve dans les barges debarque ».

Lu (rien codé) : aujourd'hui `Combat::debarquer` fait débarquer TOUTES les troupes présentes sur la planète (libres ou embarquées sur n'importe quel vaisseau), et le bouton « Débarquer N troupes d'assaut » (`model/affichePlanete.php`) apparaît dès qu'il y a une troupe et que la défense est à 0. Le tutoriel (étape « Préparer l'assaut ») demande 1 frégate + 3 troupes + 3 chasseurs, sans barge.

Ce que je comprends :
- L'action « Débarquer » de l'assaut n'est active que s'il y a au moins une barge de débarquement sur la planète (défense à 0).
- Seules les troupes embarquées DANS les barges débarquent ; troupes libres ou embarquées sur frégate ou capital ne débarquent pas.
- Le tutoriel doit donc faire produire une barge (texte et objectif de l'étape), et embarquer les troupes dedans.

## Questions en suspens

1. (infos de l'explorateur) Forme : une fenêtre (popin) qui reste jusqu'à ce que tu la fermes, comme les rapports de combat (recommandé), ou le même bandeau mais qui reste plus longtemps ?
2. (infos de l'explorateur) Le 4e cas, « revenu sans rien trouver », a-t-il aussi son info ?
3. (infos de l'explorateur) Textes courts proposés : « Système découvert : <nom> (<n> planètes) », « Planète analysée : <nom> », « Signal perdu : plus de nouvelles de votre vaisseau de reconnaissance ». Ça te va ?
4. (curseurs) Minimum 1 seulement pour la production, en gardant 0 pour l'embarquement et la création de flotte ?
5. (assaut) Tutoriel : objectif = 1 frégate (elle détruit la défense avec les chasseurs) + 1 barge + 3 troupes + 3 chasseurs ; envoi = troupes embarquées dans la barge ? Le bouton « Débarquer » de la page Flottes (qui redescend les embarqués d'un vaisseau) garde-t-il son nom, malgré la confusion possible avec l'assaut (« Décharger » en alternative) ?
6. (assaut) Une barge dans une flotte peut-elle débarquer ses troupes ?
