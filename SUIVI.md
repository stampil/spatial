# SUIVI

## À faire

### 1. Pull request, commits et push (23/11)

Ta phrase (23/11) : « tu pushera la PR mais tu ne la fusionne pas sur master sa sera a moi de le faire ». Ta phrase (24/11) : « push et merge sur le depot ». Compris : la PR 1 de `revival` vers `master` (https://github.com/stampil/spatial/pull/1) se fusionne sur ta demande explicite seulement ; le 24/11 tu me l'as demandé, je pousse `revival` puis je fusionne.

Ta phrase : « si si tu push with lease et si tu doit reprendree, tu amend ». Ta phrase : « revival c ta branche de travail a toi ». Compris : je pousse `revival` avec `--force-with-lease`, j'amende pour reprendre un travail déjà commité, un nouveau sujet fait un nouveau commit, et je ne touche jamais à `master`.

### 2. Tester avec ta base (23/11)

Avant de tester sur ta base : exécuter `database/migration_revival.sql`. Il ajoute les colonnes et crée les tables des nouvelles fonctions (camps, vaisseaux, flottes, découvertes, persos en voyage, rôles, carte avec coordonnées des systèmes, bonus de planète, départ des trajets, journal des événements, régénération de la défense, détournement, défis pirates, raids de l'IA, Pirates jouables) et note les migrations déjà appliquées. Testé sur l'ancien schéma : le résultat est identique au nouveau `database/systeme.sql`.

Vide aussi la table `joueur` (anciens hash `crypt()`, et aucun camp pour les anciens joueurs) et renseigne `inc/config.php`.

Ensuite, peuple l'univers une seule fois : `php database/peupler.php` (16 systèmes : 6 d'Empire, 3 de Pirates, 3 disputés, 4 sans camp). Il refuse de tourner si le jeu est déjà peuplé.

### 3. Garder l'essence du développeur d'origine (23/11)

Ta phrase : « garde la structure du code l'essence du développeur qui l'a fait. ». Compris : règle pour tout le travail à venir. Le code garde la structure d'origine : une classe par objet (`Joueur`, `Perso`, `Planete`, `Systeme`), `MyPDO::getInstance()->query()` avec `MyPDO::DB_FLAG`, pages dans `model/`, pas de framework. Les idées de gameplay du `Readme.md` (empire, rébellion, pirate, combats en 3 phases, usines à slots) restent la référence : je ne les remplace pas par les miennes.

### 4. Publication sur InfinityFree (23/11)

Ta phrase (25/11) : « tu n'aura pas l'adresse du site, juste tu publie quand je te le demande, sans poser de question ». Compris : je ne demande plus l'adresse du site et je ne vérifie plus le site en ligne. Publier = pousser `revival` avec `--force-with-lease` (le workflow Publish envoie le site par FTP), uniquement quand tu me le demandes, sans te poser de question avant ni après. Le site installe lui-même sa base à la première visite.

### 7. Un jeu graphique comme un jeu vidéo, avec des prompts d'images photo-réalistes (25/11)

Ta phrase (25/11) : « il faut fairele jeu + graphique comme un jeu vidéo, donner des prompt pour faire generer a une ia d'image des images du jeu et de ses icones, je veux un style photo-réaliste ». Ta phrase (25/11) : « go 5 avec tes recommandation ». Ta phrase (25/11) : « c gemini il sais pas faire de fond transparent, fait lui faire un fond magenta sans degradé ni flou ».

Ta phrase (25/11) : « ben go la faut enrichir d'image ! ».

Fait de mon côté : les planètes tournent comme des globes (avec les 26 images déjà là), et le jeu affiche une image à la place de l'icône SVG dès que le fichier existe (bâtiments, vaisseaux, rôles, bonus, bannières de camps, bandeaux de chaque page, fond des combats, fond de carte, accueil), et garde l'icône sinon. Les 34 prompts sont dans `tools/prompts-images.md` (fond magenta pur pour les objets) et `tools/detourer-magenta.py` transforme le magenta en transparence, met en 512 px et range dans `img/`.

Ta phrase (25/11) : « les prompt tu me les donne 1 par 1 ici ». Compris : je te donne ici, dans la conversation, un prompt complet et prêt à copier (bloc style, bloc magenta si objet, puis le prompt) à chaque message, dans l'ordre du fichier `tools/prompts-images.md`, et le suivant quand tu me le demandes.

Ta phrase (25/11) : « généré, mais ssa fait tres sttarwars la, je veux pas me prendre une flopé d'avocat ». Compris : les 6 premiers prompts donnaient des designs trop proches de Star Wars (coque en coin, troupier à armure blanche, escadre impériale). J'ai réécrit tous les prompts avec un bloc « design original » (ingénierie industrielle réaliste, pas de coque en coin, pas d'armure blanche, pas de cape ni de sabre, aucun nom de franchise) et je repars du prompt 1. Les 6 images déjà générées ne vont pas dans le jeu : à refaire avec les nouveaux prompts. Je ne peux pas garantir l'absence de risque juridique : si une image rappelle clairement une œuvre connue, on la régénère.

À toi : générer les images avec Gemini, les nommer comme indiqué, lancer `python3 tools/detourer-magenta.py <dossier>`, puis me dire de les commiter (ou me donner le dossier). Je n'ai pas pu tester avec de vraies images Gemini, seulement des images factices : si un détourage bave, donne-moi un exemple.

### 6. Serveurs de test (23/11)

Ta réponse à « Je coupe les serveurs de test ? » : « Laisse-les ». PHP sur `127.0.0.1:19873` et la base privée sur `127.0.0.1:19872` restent lancés jusqu'à ta demande.

## Questions en suspens

Aucune. Règle permanente. Ta phrase : « ne me demande plus de valider, je reveiendra si sa va pas ». Compris : je ne demande plus de validation sur les choix de détail ; je les applique et tu reviens si un choix ne va pas.
