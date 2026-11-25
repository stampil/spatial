# Prompts d'images photo-réalistes (Gemini)

25 images à générer : 20 objets sur fond magenta (transformés en PNG transparent par le script) et 5 fonds plein cadre.
Les petites icônes de l'interface (menu, barre d'état, boutons) restent en SVG : elles ne se génèrent pas.

## Mode d'emploi

1. Pour chaque image, colle dans Gemini le **bloc STYLE**, puis (objets seulement) le **bloc FOND MAGENTA**, puis le prompt de l'image.
2. Enregistre le fichier avec exactement le nom indiqué (l'extension peut être `.png`, `.jpg` ou `.webp`).
3. Mets tous les fichiers dans un même dossier, puis lance :
   `python3 tools/detourer-magenta.py <dossier des images> `
   Le script supprime le magenta, cadre l'objet, le met en 512 px et range le résultat dans `img/`.
4. Les images absentes ne cassent rien : le jeu affiche l'icône SVG à la place, image par image.
5. Si le script refuse une image (« le fond n'est pas un magenta uni »), régénère-la en insistant sur le fond.

## Bloc STYLE (toutes les images)

```
Photorealistic, ultra detailed, cinematic hard science-fiction, realistic materials (brushed metal, worn paint, micro scratches, weld seams), physically accurate lighting, sharp focus, shot on a full-frame camera. No text, no letters, no logos, no watermark, no user interface.
```

## Bloc FOND MAGENTA (objets seulement : vaisseau-, batiment-, role-, bonus-)

```
The subject is isolated on a perfectly flat, uniform, solid pure magenta background (hex #FF00FF, RGB 255 0 255) that fills the whole image up to the edges: no gradient, no vignette, no blur, no noise, no texture, no floor, no cast shadow, no reflection, no glow or atmosphere around the subject. The subject is centered and fully inside the frame with a clear margin on every side, with crisp sharp edges. The subject itself contains no magenta, pink or purple tones. Square image, 1:1.
```

## Vaisseaux : `vaisseau-<type>`

| Fichier | Prompt |
|---|---|
| `vaisseau-chasseur` | `A compact single-seat space fighter, angular armored hull in matte dark grey with small yellow hazard markings, twin ion engines at the rear, two nose-mounted cannons, small cockpit canopy of dark tinted glass. Three-quarter front view, slightly from above.` |
| `vaisseau-reconnaissance` | `A slender reconnaissance scout ship, very light, long narrow fuselage, a large deployable sensor dish and antenna array on top, small thrusters, white and grey panels with orange safety stripes. Three-quarter front view, slightly from above.` |
| `vaisseau-transport_perso` | `A sleek fast personal transport shuttle for two passengers, aerodynamic rounded hull in pearl white and light grey, a row of small passenger windows, short swept wings, two bright engine nozzles. Three-quarter front view, slightly from above.` |
| `vaisseau-fregate` | `A medium military frigate about 150 meters long, long armored hull with layered plating, dorsal and ventral turret batteries, a bridge tower at the rear, four large engines, dark gunmetal with worn paint. Three-quarter front view, slightly from above.` |
| `vaisseau-barge` | `A heavy troop landing barge, bulky boxy armored hull, a huge rear loading ramp lowered, thick landing gear, a visible troop bay with seats, dirty industrial grey and olive paint, slow and sturdy look. Three-quarter front view, slightly from above.` |
| `vaisseau-capital` | `A colossal capital warship, wedge-shaped hull hundreds of meters long, dozens of turrets, hangar bays on the flanks, a command tower, an enormous engine array, dark steel with fine panel lines and thousands of tiny window lights. Three-quarter front view from slightly below, imposing.` |
| `vaisseau-troupe_assaut` | `A single heavily armored assault trooper standing in a ready pose, full body, matte dark armor with scuffs, helmet with a dark visor, carrying a compact assault rifle. Three-quarter front view.` |

## Bâtiments : `batiment-<clé>`

Chaque bâtiment est posé sur une petite plateforme plate, sans décor autour.

| Fichier | Prompt |
|---|---|
| `batiment-usine_legere` | `A light spacecraft factory: a long industrial hangar with a gantry crane, several small fighters in assembly jigs visible through the open bay, bright welding lights, corrugated metal and pipes. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |
| `batiment-usine_moyenne` | `A medium shipyard: a large open-frame gantry holding a half-built frigate hull, cranes, scaffolding, work lights, industrial details. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |
| `batiment-usine_lourde` | `A massive drydock: giant gantry towers holding the skeleton of a capital ship hull, huge cranes, steam vents, sparks of welding. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |
| `batiment-ferme` | `A high-tech hydroponic farm: a row of translucent greenhouse domes full of lush green crops, irrigation pipes, solar panels, a small silo, warm grow lights. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |
| `batiment-academie` | `A military academy: an imposing symmetrical facade of stone and steel with columns and a tall central tower, a parade ground with tiny cadets in formation, an empty flagpole without any flag. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |

## Portraits de rôles : `role-<rôle>`

| Fichier | Prompt |
|---|---|
| `role-diplomate` | `Waist-up portrait of an elegant diplomat envoy in his forties, formal dark-blue high-collar uniform with silver trim, calm confident expression, holding a datapad, no weapon. Three-quarter view.` |
| `role-heros` | `Waist-up portrait of a battle-hardened hero commander in her forties, a scar on her cheek, practical dark armor with a short cape, determined stare, slightly low heroic angle. Three-quarter view.` |
| `role-assassin` | `Waist-up portrait of a stealthy assassin operative, hooded matte-black stealth suit, the lower face covered by a mask leaving only intense eyes visible, a thin blade at the belt, tense ready posture. Three-quarter view.` |

## Bonus de planète : `bonus-<bonus>`

| Fichier | Prompt |
|---|---|
| `bonus-chantier` | `An orbital construction dock: a gigantic crane arm lifting a heavy armor plate onto the keel of a capital ship, warm yellow work lights. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |
| `bonus-forge` | `A fighter forge: an industrial forge hall where a robotic arm presses a glowing hot metal wing plate on an anvil, orange molten glow on the object only, tools and tongs. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |
| `bonus-marche` | `A bustling interstellar market hub: stacked cargo crates and stalls with green and orange awnings, merchants, hovering cargo drones, holographic price boards without readable text. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |
| `bonus-citadelle` | `A planetary citadel fortress: thick angular bastion walls, heavy defense cannons on towers, an energy shield generator dish glowing pale blue, an armored gate. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |
| `bonus-relais` | `A hyperspace relay station: a tall antenna mast array with a large parabolic dish and a glowing beacon ring, solar panels, a small control cabin. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |

## Fonds plein cadre (sans magenta, 16:9, bloc STYLE seulement)

| Fichier | Prompt |
|---|---|
| `camp-pirates` | `Wide 16:9 cinematic shot of a ragged pirate fleet hiding in a dense asteroid field, mismatched scavenged ships with patched hulls and red warning lights, red-orange rim light, floating dust, menacing mood.` |
| `camp-empire` | `Wide 16:9 cinematic shot of a disciplined imperial armada in perfect formation above a gleaming metropolis planet, clean white and blue-steel hulls, cold blue light, strict symmetry, grandeur.` |
| `camp-rebellion` | `Wide 16:9 cinematic shot of a hidden rebel base: small mismatched ships landed in a camouflaged hangar carved into a forested moon cliff, soft green light, mist, a secretive and hopeful mood.` |
| `carte` | `Wide 16:9 deep-space backdrop: a dark star field with a subtle nebula in deep indigo and teal, very low contrast and mostly dark so that map markers drawn on top stay readable, evenly distributed, no planets, no ships, no text.` |
| `accueil` | `Wide 16:9 epic cinematic key art: three fleets of different styles converging over a ringed planet, dramatic lighting and vast scale, the central area calmer and darker to leave room for a title written on top.` |
