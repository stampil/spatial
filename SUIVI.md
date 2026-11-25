# SUIVI

## À faire

### 1. Pluriel des nombres (25/11)

Ta phrase : « 1 planètes j'ai horreur quand tu fait ça, un pauvre ternaire ou un if sa mange pas de pain, mais gere ce pluriel ! ». Ta phrase : « non enrichi la collone de traduction et en 2ieme param c le mot au pluriel ». Ta phrase : « __() ». Ta phrase : « go pour tout ».

Compris : un fichier de traduction français (clé → mot) et `__($cle, $clePluriel, $nombre)`, d'après la `__()` d'`ehowww`. Appel : `__('planete', 'planetes', $nombre)`. Appliqué à tous les « planète(s) », « vaisseau(x) », « troupe(s) », « perso(s) », « déplacement(s) », « emplacements », « barge(s) », « minutes », « points », y compris l'accord du verbe.

### 2. Bouton Migration dans le menu du haut (25/11)

Ta phrase : « j'execute rien sur le serveur, fait dnas le menu header un btn migration et au clique sa lance ton sql ». Ta phrase : « Q1 oui y a que emoi qui test pour l'instant surle serveur ». Ta phrase : « Q2 laissse le sans logique de suppression mais au prochain commit tu l'lelve ». Ta phrase : « je vois pas le btn ! ».

Compris : le bouton lance `database/migration_revival.sql`, supprime les anciens comptes joueurs (et planètes, systèmes, persos, productions liés), applique les migrations 011 à 016 et peuple l'univers, après confirmation. Il est toujours affiché (menu du haut des joueurs connectés, et page minimale sur l'ancienne base qui bloque `index.php` avant tout menu). Son action refuse de rejouer sur une base déjà migrée.

### 3. Retirer le bouton Migration au prochain commit (25/11)

Ta phrase : « au prochain commit tu l'lelve ». Compris : le commit qui suit celui du bouton supprime le bouton, la page minimale et le code de migration.

## Questions en suspens

Aucune.
