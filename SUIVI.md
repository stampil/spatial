# SUIVI

## À faire

### 1. Une icône pour faire de la diplomatie, et plus aucun bouton d'action en texte (26/11)

Ta phrase : « fait une icone pour faire d ela diplomatie ! je ne veux plus de btn d'ction en text !! ».

Lu (rien codé) : il reste des boutons d'action en texte dans 4 endroits.
- Fiche d'un perso (`model/affichePerso.php`) : « Faire de la diplomatie », « Apaiser la révolte », « Assassiner » (2 fois : un bouton à icône, un champ texte), « Saboter », « Monter dans un transport », « Descendre du transport ».
- Fiche de planète (`inc/planeteGestion.php`) : « Construire », « Démolir (+N cr) », « Recruter : rôle (prix cr) », « Produire ».
- Combat : « Attaquer », « Attaquer » (flotte), « Débarquer », « Combattre », « Laisser » (icône + mot), et la fenêtre de rapport « Résoudre », « Passer », « Fermer ».
- Fiche de système et accueil : « Donner des ordres », « Voir sur la carte », « Voir mes persos », « Liste des persos ».
- Page Flottes : « Créer une flotte », « Enregistrer », « Annuler », « Renommer » / « Embarquer » dans les panneaux dépliés.

Ce que je comprends : chaque action du jeu devient un bouton icône seule (infobulle + nom accessible), comme sur les vaisseaux, avec une icône dédiée pour « faire de la diplomatie » (poignée de main, blanc et gris foncé comme les autres).

Chiffres (« pas de chiffre dans les boutons », puis « Oui, à côté » le 26/11) : aucun chiffre dans un bouton ; les coûts (« +N cr », « 500 cr ») et le nombre de vaisseaux près d'« Attaquer » restent en petit texte à côté de l'icône, hors du bouton, sauf pour la diplomatie (voir point 2).

### 2. Diplomatie : ne plus afficher la durée ni la chance (26/11)

Ta phrase : « n'affiche pas la durée ni la chance pour la displomatie ».

Lu (rien codé) : sur la fiche d'un perso (`model/affichePerso.php`), la ligne « Faire de la diplomatie » affiche « sur Planète (durée mm:ss, chance de réussite N %) », et « Apaiser la révolte » affiche « (durée mm:ss) ». Ce que je comprends : l'icône de diplomatie est suivie seulement de « sur Planète », sans durée ni chance. Le calcul de la durée et de la chance reste (il sert à lancer l'action et à tirer le résultat), seul l'affichage disparaît.

## Questions en suspens

1. Périmètre, corrigé : « putain je t'avais dit que je vouais plus voir donner des ordre merde ». J'avais classé « Donner des ordres » (fiche de système) parmi les boutons de navigation à laisser en texte : c'était faux, c'est un bouton d'action. Passent donc aussi en icône seule : « Donner des ordres » (icône voyager), « Voir sur la carte » (icône carte), « Voir mes persos » et « Liste des persos » (icône persos), en plus de toutes les actions du jeu. Il ne reste en texte que les boutons de compte et de validation : « Se connecter », « S'inscrire », « Inscription », « Connexion », « J'ai compris », « Fermer ». Je recommande ce découpage ; à toi de dire si ces six doivent aussi devenir des icônes.
2. Les icônes qui existent déjà sont réutilisées : construire, assassin, saboter, embarquer (monter dans un transport), décharger (descendre), carte, flotte, attaque, débarquement, combattre, laisser, renommer. Nouvelles images à générer avec toi, une par une : négocier (diplomatie, poignée de main), apaiser (révolte), démolir, recruter, produire, résoudre (rapport), passer, créer une flotte. OK pour cette liste de 8 ?
3. Même règle pour « Apaiser la révolte » (durée) : c'est aussi de la diplomatie sur la même ligne. Je recommande oui.
4. L'assassinat affiche aussi « chance de réussite N %, en cas d'échec : prison ou mort » : tu ne l'as pas citée, je la laisse telle quelle. À confirmer.
