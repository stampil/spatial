# SUIVI

## À faire

### 1. Bouton Reset numéroté (26/11)

Ta phrase : « le btn reset ne doit pas s'appeler migration mais reset 1 que tu incrémentera en fonctio ndes besoin ». Le bouton s'appelle « Reset 1 » (constante `RESET_NUMERO` dans `inc/const.php`). J'augmente le numéro quand un nouveau reset est nécessaire. Le bouton part quand tu me le diras.

### 2. Tuto : la mission du perso doit dire de faire la diplomatie (26/11)

Ta phrase : « tuto mission : les caractérisquers de votre perso... ben je suis bloquer sa me dit pas quoi faire, c'etais sencé dire de faire la diplomatie, ta fait une regression ». Vérifié : sur la fiche du perso, le texte « Mission : les caractéristiques de votre perso » n'a jamais dit de lancer la diplomatie (même texte dans la toute première version) ; il explique les étoiles et dit seulement que les actions s'affichent « de manière contextuelle ». Le bouton « Faire de la diplomatie » existe bien, dans la section « Actions » plus bas, mais rien ne t'y envoie. Je n'ai pas pu reproduire un bug de code : une inscription neuve affiche bien le bouton (durée 00:40, réussite 100 %), sans erreur. À faire : le texte de la mission dit explicitement « Lancez la diplomatie sur votre planète natale : bouton « Faire de la diplomatie » dans Actions, plus bas » avec un lien vers cette section ; pendant la diplomatie, il dit d'attendre la fin du compte à rebours. Même clarté pour la mission de la liste des persos (« Touchez votre perso »).

## Questions en suspens

Aucune.
