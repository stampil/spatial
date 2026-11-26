# Prompts d'images photo-réalistes (Gemini)

37 images à générer : 23 objets sur fond magenta (transformés en PNG transparent par le script) et 14 fonds plein cadre, plus 21 vaisseaux par camp (section « Vaisseaux par camp »).
Les petites icônes de l'interface (menu, barre d'état, boutons) restent en SVG : elles ne se génèrent pas.

Les designs sont volontairement originaux : aucune silhouette, aucun nom ni aucun style reconnaissable d'une franchise existante. Aucune garantie juridique n'est possible, mais ne garde pas une image qui rappelle clairement une œuvre connue : régénère-la.

## Mode d'emploi

1. Pour chaque image, colle dans Gemini, dans cet ordre : le **bloc STYLE**, le **bloc DESIGN ORIGINAL**, le **bloc FOND MAGENTA** (objets seulement), puis le prompt de l'image.
2. Enregistre le fichier avec exactement le nom indiqué (l'extension peut être `.png`, `.jpg` ou `.webp`).
3. Mets tous les fichiers dans un même dossier, puis lance :
   `python3 tools/detourer-magenta.py <dossier des images>`
   Le script supprime le magenta, cadre l'objet, le met en 512 px et range le résultat dans `img/`.
4. Les images absentes ne cassent rien : le jeu affiche l'icône SVG à la place, image par image.
5. Si le script refuse une image (« le fond n'est pas un magenta uni »), régénère-la en insistant sur le fond.

## Bloc STYLE (toutes les images)

```
Photorealistic, ultra detailed, documentary realism, realistic materials (brushed metal, worn paint, micro scratches, weld seams), physically accurate lighting, sharp focus, shot on a full-frame camera. No text, no letters, no logos, no watermark, no user interface.
```

## Bloc DESIGN ORIGINAL (toutes les images)

```
Completely original design, not resembling any existing film, series, game, comic or franchise. Realistic industrial engineering look: modular cylindrical and boxy sections like orbital stations and cargo freighters, exposed truss frames, large radiator panels, fuel tanks, mast antennas, asymmetrical layouts, weathered muted colors (rust, ochre, teal, olive, off-white). Avoid wedge-shaped or triangular hulls, avoid symmetrical twin-fin or X-shaped fighters, avoid white plastic armor with black visors, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
```

## Bloc FOND MAGENTA (objets seulement : vaisseau-, batiment-, role-, bonus-)

```
The subject is isolated on a perfectly flat, uniform, solid pure magenta background (hex #FF00FF, RGB 255 0 255) that fills the whole image up to the edges: no gradient, no vignette, no blur, no noise, no texture, no floor, no cast shadow, no reflection, no glow or atmosphere around the subject. The subject is centered and fully inside the frame with a clear margin on every side, with crisp sharp edges. The subject itself contains no magenta, pink or purple tones. Square image, 1:1.
```

## Vaisseaux : `vaisseau-<type>`

| Fichier | Prompt |
|---|---|
| `vaisseau-chasseur` | `A compact single-seat spacecraft built around a large cylindrical fuel tank with a rear cluster of three small engines, an exposed truss frame, the cockpit as a small offset bubble module, one cannon on a long boom under the nose, ochre and grey paint with scuffs. Three-quarter front view, slightly from above.` |
| `vaisseau-reconnaissance` | `A small reconnaissance craft: a long thin central boom carrying a large folded sensor dish, two foldable solar panel wings, a small cylindrical body with a tiny thruster block, off-white foil insulation and teal panels. Three-quarter front view, slightly from above.` |
| `vaisseau-transport_perso` | `A compact personal transport capsule for two passengers: a rounded capsule body with a row of small round portholes, two side-mounted tank modules, a short docking collar on the nose, a single large engine bell at the rear, off-white and teal paint. Three-quarter front view, slightly from above.` |
| `vaisseau-fregate` | `A medium military frigate about 150 meters long built along a long central truss spine: stacked cylindrical modules, two large radiator wings, armored turret blisters, a forward crew module and a rear engine block with three engine bells, olive and rust paint, no wedge or triangular shape. Three-quarter front view, slightly from above.` |
| `vaisseau-barge` | `A heavy landing craft shaped like a flying shipping container with a blunt rounded nose, a large drop ramp on the underside, four thick landing legs, a visible troop bay with bench seats, rust-orange and grey paint with weathering. Three-quarter front view, slightly from above.` |
| `vaisseau-capital` | `A colossal capital ship built as a very long cylindrical spine with large spherical fuel tanks, stacked habitat rings, huge radiator wings, dozens of turret blisters and a rear engine array, hundreds of meters long, steel grey and ochre with thousands of tiny window lights, no wedge or triangular shape. Three-quarter front view from slightly below, imposing.` |
| `vaisseau-troupe_assaut` | `A single soldier in a ready pose, full body, modular dark olive and sand-colored combat gear with knee and elbow pads, an open-face helmet with goggles, a backpack with life-support tubes, a rifle with realistic proportions, practical and worn, no white armor, no black full-face visor. Three-quarter front view.` |

## Vaisseaux par camp : `vaisseau-<camp>-<type>`

Chaque camp a son propre look. Pour ces 21 images, utilise le **bloc STYLE**, le **bloc DESIGN du camp** ci-dessous (à la place du bloc DESIGN ORIGINAL), le **bloc FOND MAGENTA**, puis le prompt. Le script range `vaisseau-pirates-chasseur` dans `img/vaisseau/pirates/chasseur.png` ; sans image de camp, le jeu retombe sur l'image générique `vaisseau-<type>`.

### Bloc DESIGN PIRATES

```
Completely original design, not resembling any existing film, series, game, comic or franchise. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible. Avoid wedge-shaped or triangular hulls, avoid symmetrical twin-fin or X-shaped fighters, avoid white plastic armor with black visors, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
```

### Bloc DESIGN REBELLION

```
Completely original design, not resembling any existing film, series, game, comic or franchise. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care. Avoid wedge-shaped or triangular hulls, avoid symmetrical twin-fin or X-shaped fighters, avoid white plastic armor with black visors, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
```

### Bloc DESIGN EMPIRE

```
Completely original design, not resembling any existing film, series, game, comic or franchise. Imperial faction ships: clean polished factory-fresh vessels sharing one unified design language across the whole fleet: smooth elongated rounded hulls with a continuous dorsal spine, ring-shaped engine nozzles, thin recessed light strips, flush panel lines, pale grey and bone-white hulls with a single deep blue accent stripe, symmetrical layout, immaculate finish. Avoid wedge-shaped or triangular hulls, avoid symmetrical twin-fin or X-shaped fighters, avoid white plastic armor with black visors, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
```

### Blocs DESIGN des troupes (à utiliser à la place du bloc DESIGN du camp pour `vaisseau-<camp>-troupe_assaut`)

#### PIRATES

```
Completely original design, not resembling any existing film, series, game, comic or franchise. Pirate faction soldier: a scavenger fighter in mismatched looted high-tech armor plates of different colors, a scrapped sensor-band helmet with taped-on goggles, a ragged cloth wrap, cables and ammunition cells strapped on, a scavenged futuristic carbine repaired with duct tape, rust and grime. Strongly futuristic far-future science-fiction infantry look: advanced composite armor plates over a sealed bodysuit, a modern ergonomic helmet with an integrated sensor band and breathing mask, a compact futuristic carbine with a polymer body, a power pack and optical sights, hard-light wrist display, utility pouches of high-tech fabric. Absolutely not a 20th-century or World War II look: no steel pot helmet, no wooden rifle stock, no canvas webbing, no trench coat, no historical uniform. Avoid wedge-shaped or triangular shapes, avoid white plastic armor with black visors, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
```

#### REBELLION

```
Completely original design, not resembling any existing film, series, game, comic or franchise. Rebel faction soldier: a veteran fighter in worn, repainted, faded-green composite armor over a patched bodysuit, a scarf, an old scratched sensor-band helmet, a well-used futuristic carbine, tired but disciplined. Strongly futuristic far-future science-fiction infantry look: advanced composite armor plates over a sealed bodysuit, a modern ergonomic helmet with an integrated sensor band and breathing mask, a compact futuristic carbine with a polymer body, a power pack and optical sights, hard-light wrist display, utility pouches of high-tech fabric. Absolutely not a 20th-century or World War II look: no steel pot helmet, no wooden rifle stock, no canvas webbing, no trench coat, no historical uniform. Avoid wedge-shaped or triangular shapes, avoid white plastic armor with black visors, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
```

#### EMPIRE

```
Completely original design, not resembling any existing film, series, game, comic or franchise. Imperial faction soldier: a trooper in matching clean composite armor in pale grey with a single deep blue stripe, a standardized sealed helmet with an integrated sensor band, a neat futuristic carbine, immaculate and uniform, no white plastic armor, no black visor. Strongly futuristic far-future science-fiction infantry look: advanced composite armor plates over a sealed bodysuit, a modern ergonomic helmet with an integrated sensor band and breathing mask, a compact futuristic carbine with a polymer body, a power pack and optical sights, hard-light wrist display, utility pouches of high-tech fabric. Absolutely not a 20th-century or World War II look: no steel pot helmet, no wooden rifle stock, no canvas webbing, no trench coat, no historical uniform. Avoid wedge-shaped or triangular shapes, avoid white plastic armor with black visors, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
```

| Fichier | Prompt |
|---|---|
| `vaisseau-pirates-chasseur` | `A compact single-seat combat craft with a small cockpit bubble, a cluster of small engines at the rear and one cannon under the nose, small enough to be carried inside a larger ship. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-reconnaissance` | `A small reconnaissance craft: a thin central boom carrying a large sensor dish, folding solar panel wings, a small body and a tiny thruster block. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-transport_perso` | `A compact personal transport capsule for two passengers: a rounded body with a row of small portholes, a short docking collar on the nose, a single large engine bell at the rear. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-fregate` | `A medium military frigate about 150 meters long: a long hull with turret blisters, a forward crew module, a visible open hangar bay for small fighters, a troop hatch and a rear engine block. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-barge` | `A heavy landing craft shaped like a flying cargo container with a blunt rounded nose, a large drop ramp on the underside, four thick landing legs and a visible troop bay with bench seats. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-capital` | `A colossal capital ship hundreds of meters long: a very long main hull with stacked habitat sections, several open hangar bays, dozens of turret blisters, huge radiator surfaces and a rear array of large engines. Three-quarter front view from slightly below, imposing. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-troupe_assaut` | `A single soldier in a ready pose, full body, three-quarter front view, wearing futuristic armor and carrying a futuristic carbine.` |
| `vaisseau-rebellion-chasseur` | `A compact single-seat combat craft with a small cockpit bubble, a cluster of small engines at the rear and one cannon under the nose, small enough to be carried inside a larger ship. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-reconnaissance` | `A small reconnaissance craft: a thin central boom carrying a large sensor dish, folding solar panel wings, a small body and a tiny thruster block. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-transport_perso` | `A compact personal transport capsule for two passengers: a rounded body with a row of small portholes, a short docking collar on the nose, a single large engine bell at the rear. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-fregate` | `A medium military frigate about 150 meters long: a long hull with turret blisters, a forward crew module, a visible open hangar bay for small fighters, a troop hatch and a rear engine block. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-barge` | `A heavy landing craft shaped like a flying cargo container with a blunt rounded nose, a large drop ramp on the underside, four thick landing legs and a visible troop bay with bench seats. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-capital` | `A colossal capital ship hundreds of meters long: a very long main hull with stacked habitat sections, several open hangar bays, dozens of turret blisters, huge radiator surfaces and a rear array of large engines. Three-quarter front view from slightly below, imposing. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-troupe_assaut` | `A single soldier in a ready pose, full body, three-quarter front view, wearing futuristic armor and carrying a futuristic carbine.` |
| `vaisseau-empire-chasseur` | `A compact single-seat combat craft with a small cockpit bubble, a cluster of small engines at the rear and one cannon under the nose, small enough to be carried inside a larger ship. Three-quarter front view, slightly from above. Imperial faction ships: clean polished factory-fresh vessels sharing one unified design language across the whole fleet: smooth elongated rounded hulls with a continuous dorsal spine, ring-shaped engine nozzles, thin recessed light strips, flush panel lines, pale grey and bone-white hulls with a single deep blue accent stripe, symmetrical layout, immaculate finish.` |
| `vaisseau-empire-reconnaissance` | `A small reconnaissance craft: a thin central boom carrying a large sensor dish, folding solar panel wings, a small body and a tiny thruster block. Three-quarter front view, slightly from above. Imperial faction ships: clean polished factory-fresh vessels sharing one unified design language across the whole fleet: smooth elongated rounded hulls with a continuous dorsal spine, ring-shaped engine nozzles, thin recessed light strips, flush panel lines, pale grey and bone-white hulls with a single deep blue accent stripe, symmetrical layout, immaculate finish.` |
| `vaisseau-empire-transport_perso` | `A compact personal transport capsule for two passengers: a rounded body with a row of small portholes, a short docking collar on the nose, a single large engine bell at the rear. Three-quarter front view, slightly from above. Imperial faction ships: clean polished factory-fresh vessels sharing one unified design language across the whole fleet: smooth elongated rounded hulls with a continuous dorsal spine, ring-shaped engine nozzles, thin recessed light strips, flush panel lines, pale grey and bone-white hulls with a single deep blue accent stripe, symmetrical layout, immaculate finish.` |
| `vaisseau-empire-fregate` | `A medium military frigate about 150 meters long: a long hull with turret blisters, a forward crew module, a visible open hangar bay for small fighters, a troop hatch and a rear engine block. Three-quarter front view, slightly from above. Imperial faction ships: clean polished factory-fresh vessels sharing one unified design language across the whole fleet: smooth elongated rounded hulls with a continuous dorsal spine, ring-shaped engine nozzles, thin recessed light strips, flush panel lines, pale grey and bone-white hulls with a single deep blue accent stripe, symmetrical layout, immaculate finish.` |
| `vaisseau-empire-barge` | `A heavy landing craft shaped like a flying cargo container with a blunt rounded nose, a large drop ramp on the underside, four thick landing legs and a visible troop bay with bench seats. Three-quarter front view, slightly from above. Imperial faction ships: clean polished factory-fresh vessels sharing one unified design language across the whole fleet: smooth elongated rounded hulls with a continuous dorsal spine, ring-shaped engine nozzles, thin recessed light strips, flush panel lines, pale grey and bone-white hulls with a single deep blue accent stripe, symmetrical layout, immaculate finish.` |
| `vaisseau-empire-capital` | `A colossal capital ship hundreds of meters long: a very long main hull with stacked habitat sections, several open hangar bays, dozens of turret blisters, huge radiator surfaces and a rear array of large engines. Three-quarter front view from slightly below, imposing. Imperial faction ships: clean polished factory-fresh vessels sharing one unified design language across the whole fleet: smooth elongated rounded hulls with a continuous dorsal spine, ring-shaped engine nozzles, thin recessed light strips, flush panel lines, pale grey and bone-white hulls with a single deep blue accent stripe, symmetrical layout, immaculate finish.` |
| `vaisseau-empire-troupe_assaut` | `A single soldier in a ready pose, full body, three-quarter front view, wearing futuristic armor and carrying a futuristic carbine.` |

## Bâtiments : `batiment-<clé>`

Chaque bâtiment est posé sur une petite plateforme plate, sans décor autour.

| Fichier | Prompt |
|---|---|
| `batiment-usine_legere` | `A light spacecraft factory: a long industrial hangar with a gantry crane, several small spacecraft in assembly jigs visible through the open bay, bright welding lights, corrugated metal and pipes. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |
| `batiment-usine_moyenne` | `A medium shipyard: a large open-frame gantry holding a half-built ship hull made of cylindrical modules, cranes, scaffolding, work lights, industrial details. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |
| `batiment-usine_lourde` | `A massive drydock: giant gantry towers holding the skeleton of a very large ship hull, huge cranes, steam vents, sparks of welding. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |
| `batiment-ferme` | `A high-tech hydroponic farm: a row of translucent greenhouse domes full of lush green crops, irrigation pipes, solar panels, a small silo, warm grow lights. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |
| `batiment-academie` | `A military academy: an imposing facade of concrete and steel with columns and a tall central tower, a parade ground with tiny cadets in formation, an empty flagpole without any flag. Standing on a small flat platform, no surrounding terrain. Three-quarter view from above.` |

## Portraits de rôles : `role-<rôle>`

| Fichier | Prompt |
|---|---|
| `role-diplomate` | `Waist-up portrait of an elegant diplomat in his forties, a tailored charcoal coat with a high collar and subtle silver piping, calm confident expression, holding a datapad, no weapon. Three-quarter view.` |
| `role-heros` | `Waist-up portrait of a battle-hardened commander in her forties, a scar on her cheek, a practical dark composite armor vest over a flight suit, no cape, determined stare, slightly low heroic angle. Three-quarter view.` |
| `role-assassin` | `Waist-up portrait of a stealthy operative wearing a dark urban tactical jacket with a hood, a scarf covering the lower face leaving only intense eyes visible, a thin blade at the belt, tense ready posture. Three-quarter view.` |

## Bonus de planète : `bonus-<bonus>`

| Fichier | Prompt |
|---|---|
| `bonus-chantier` | `An orbital construction dock: a gigantic crane arm lifting a heavy armor plate onto the keel of a large ship, warm yellow work lights. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |
| `bonus-forge` | `A spacecraft forge: an industrial forge hall where a robotic arm presses a glowing hot metal plate on an anvil, orange molten glow on the object only, tools and tongs. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |
| `bonus-marche` | `A bustling interstellar market hub: stacked cargo crates and stalls with green and orange awnings, merchants, hovering cargo drones, holographic price boards without readable text. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |
| `bonus-citadelle` | `A planetary citadel fortress: thick bastion walls of concrete, heavy defense cannons on towers, an energy shield generator dish glowing pale blue, an armored gate. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |
| `bonus-relais` | `A hyperspace relay station: a tall antenna mast array with a large parabolic dish and a glowing beacon ring, solar panels, a small control cabin. Standing on a small flat platform, no surrounding terrain. Three-quarter view.` |

## Fonds plein cadre (sans magenta, 16:9, blocs STYLE et DESIGN ORIGINAL seulement)

| Fichier | Prompt |
|---|---|
| `camp-pirates` | `Wide 16:9 cinematic shot of a ragged pirate fleet hiding in a dense asteroid field, mismatched scavenged ships with patched hulls and red warning lights, red-orange rim light, floating dust, menacing mood.` |
| `camp-empire` | `Wide 16:9 cinematic shot of a disciplined fleet of identical blue-grey cylindrical ships in a strict grid formation above a planet with a thin orbital ring station, cold blue light, strict symmetry, grandeur.` |
| `camp-rebellion` | `Wide 16:9 cinematic shot of a hidden base: small mismatched ships landed under camouflage nets in a rocky desert canyon, soft green light, a secretive and hopeful mood.` |
| `carte` | `Wide 16:9 deep-space backdrop: a dark star field with a subtle nebula in deep indigo and teal, very low contrast and mostly dark so that map markers drawn on top stay readable, evenly distributed, no planets, no ships, no text.` |
| `accueil` | `Wide 16:9 epic cinematic key art: three fleets of different styles converging over a ringed planet, dramatic lighting and vast scale, the central area calmer and darker to leave room for a title written on top.` |
| `combat` | `Wide 16:9 cinematic space battle: spacecraft exchanging tracer rounds and missile trails in front of a burning planet, explosions and debris, dramatic orange and blue lighting, the action in the horizontal middle band.` |

## Bandeaux de pages (sans magenta, 16:9, blocs STYLE et DESIGN ORIGINAL seulement)

Ces images sont affichées en bandeau large et bas (environ 3:1) en haut de chaque page : le sujet doit être dans la bande horizontale du milieu, le haut et le bas restent calmes.

| Fichier | Prompt |
|---|---|
| `page-tableauBord` | `Wide 16:9 cinematic shot of a futuristic command center with a large holographic star map and officers at consoles, cool blue light, the subject in the horizontal middle band.` |
| `page-listePlanete` | `Wide 16:9 cinematic shot of a colony world seen from orbit at the day-night line, glowing city lights and a thin blue atmosphere, the planet horizon crossing the horizontal middle band.` |
| `page-listeVaisseau` | `Wide 16:9 cinematic shot of a long row of docked spacecraft in a vast hangar bay, perspective lines, warm work lights, the ships in the horizontal middle band.` |
| `page-listePerso` | `Wide 16:9 cinematic shot of a briefing room where a diplomat, a commander and a hooded operative stand around a table with a holographic map, dim moody light, the group in the horizontal middle band.` |
| `page-classement` | `Wide 16:9 cinematic shot of a grand hall of fame in space: rows of illuminated trophies and plain banners without any emblem, golden light, symmetry, the trophies in the horizontal middle band.` |
| `page-listeCamp` | `Wide 16:9 cinematic shot of three distinct fleets side by side in deep space, one ragged with red lights, one orderly blue-grey, one small and green, separated by dark space, the ships in the horizontal middle band.` |
| `page-inscription` | `Wide 16:9 cinematic shot of a lone recruitment shuttle leaving a space station toward a distant glowing nebula, a feeling of departure and adventure, the shuttle in the horizontal middle band.` |
| `page-connection` | `Wide 16:9 cinematic shot of a pilot's cockpit view looking out at a star field and a distant planet, soft instrument glow on the edges, calm mood, the horizon in the horizontal middle band.` |

## Logos des camps : `logo-<camp>`

Pour les logos, remplace le bloc STYLE et le bloc DESIGN ORIGINAL par le bloc LOGO ci-dessous, puis ajoute le bloc FOND MAGENTA et le prompt. Les formes reprennent les emblèmes actuels du jeu (cercle croisé, hexagone à triangle, losange à point).

```
Photorealistic macro photograph of a physical metal badge, ultra detailed, realistic materials (hammered brass, brushed steel, enamel inlay, rivets, micro scratches), physically accurate lighting, sharp focus, shot on a full-frame camera. Completely original emblem, not resembling any existing logo, flag, insignia or franchise emblem: no skull and crossbones, no cog wheel, no bird, no wings, no star. No text, no letters, no watermark. Front view, perfectly flat-on, symmetrical, centered.
```

| Fichier | Prompt |
|---|---|
| `logo-pirates` | `A circular badge with a hammered brass rim and dark iron face, two bold crossed diagonal bars forming a large X in the center, rust-red enamel inlay in the bars, rivets and scratches, worn and scavenged look.` |
| `logo-empire` | `A hexagonal badge with a brushed silver rim and a polished blue-steel face, a solid raised equilateral triangle in the center with its apex up, fine concentric engraved lines, cold blue enamel, clean and disciplined look.` |
| `logo-rebellion` | `A diamond-shaped badge (a square rotated by 45 degrees) with a worn bronze rim and forest-green enamel face, a round raised dot in the center, slightly uneven hand-hammered and improvised look, a few rivets.` |
