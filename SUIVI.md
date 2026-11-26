# SUIVI

## À faire

### 1. Une icône pour faire de la diplomatie, et plus aucun bouton d'action en texte (26/11)

Ta phrase : « fait une icone pour faire d ela diplomatie ! je ne veux plus de btn d'ction en text !! ».

Lu (rien codé) : il reste des boutons d'action en texte dans 4 endroits.
- Fiche d'un perso (`model/affichePerso.php`) : « Faire de la diplomatie », « Apaiser la révolte », « Assassiner » (2 fois : un bouton à icône, un champ texte), « Saboter », « Monter dans un transport », « Descendre du transport ».
- Fiche de planète (`inc/planeteGestion.php`) : « Construire », « Démolir (+N cr) », « Recruter : rôle (prix cr) », « Produire ».
- Combat : « Attaquer », « Attaquer » (flotte), « Débarquer », « Combattre », « Laisser » (icône + mot), et la fenêtre de rapport « Résoudre », « Passer », « Fermer ».
- Page Flottes : « Créer une flotte », « Enregistrer », « Annuler », « Renommer » / « Embarquer » dans les panneaux dépliés.

Ce que je comprends : chaque action du jeu devient un bouton icône seule (infobulle + nom accessible), comme sur les vaisseaux, avec une icône dédiée pour « faire de la diplomatie » (poignée de main, blanc et gris foncé comme les autres).

## Questions en suspens

1. Périmètre : toutes les actions du jeu (persos, planète, combat, rapport, flottes) passent en icône seule ; les boutons de compte et de navigation (« Se connecter », « S'inscrire », « Inscription », « Connexion », « Fermer », « J'ai compris », « Voir sur la carte », « Donner des ordres », « Liste des persos ») restent en texte. Je recommande ce découpage : ce ne sont pas des actions de jeu.
2. Les coûts et les chiffres aujourd'hui dans le bouton (« Démolir (+N cr) », « Recruter : Assassin (500 cr) », « 3 vaisseaux » près d'« Attaquer ») : je les garde en petit texte à côté de l'icône, dans l'infobulle aussi. Je recommande oui.
3. Les icônes qui existent déjà sont réutilisées : construire, assassin, saboter, embarquer (monter dans un transport), décharger (descendre), carte, flotte, attaque, débarquement, combattre, laisser, renommer. Nouvelles images à générer avec toi, une par une : négocier (diplomatie, poignée de main), apaiser (révolte), démolir, recruter, produire, résoudre (rapport), passer, créer une flotte. OK pour cette liste de 8 ?
