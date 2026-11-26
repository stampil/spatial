# SUIVI

## À faire

### 1. Avatar des persos : cacher la coupure du buste (26/11)

Ta phrase : « on voit le bust coupé agrandi l'image et decalle ver slebas qu'on voiyent pas la coupure » (avec une capture : l'avatar rond du perso, le bas du buste coupé à plat bien visible dans le cercle).

Lu (rien codé) : l'image du rôle (`img/role/*.png`, 512 px) est affichée entière et centrée (`background-size: contain`) dans le cercle de 72 px (`.avatar .visuel`, `css/jeu.css`) : la coupure plate du bas du buste tombe donc à l'intérieur du cercle.

Ce que je comprends : on agrandit l'image dans le cercle et on l'aligne sur le bas, pour que la coupure plate sorte du cercle et ne se voie plus, sans couper la tête. Concerne les 3 rôles (diplomate, assassin, héros), sur la fiche et la liste des persos.

## Questions en suspens

1. Agrandissement à 125 % aligné sur le bas du cercle ; je vérifie sur les trois rôles que la tête n'est pas coupée en haut, et j'ajuste si besoin. Je recommande cela.
