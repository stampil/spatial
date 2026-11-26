# Prompts d'images photo-réalistes (Gemini)

37 images à générer : 23 objets sur fond magenta (transformés en PNG transparent par le script) et 14 fonds plein cadre, plus 21 vaisseaux par camp (section « Vaisseaux par camp »).
Les icônes de l'interface ont leur propre section « Icônes de l'interface » (glyphes duotones sobres : elles s'affichent à 24 px). Seuls les pictogrammes fonctionnels (menu, son, muet, ajouter, info) restent en SVG.

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

## Bloc FOND MAGENTA (objets seulement : vaisseau-, batiment-, role-, bonus-, icone-)

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
| `vaisseau-restrictor` | `A heavy support warship about 250 meters long: a long cylindrical central hull with fuel tanks, wrapped around its middle by a large open ring frame carrying evenly spaced field emitter pylons topped by big parabolic dishes pointing outward, long mast antennas at the front, large radiator panels, a rear engine cluster, only a few small point-defense blisters. Three-quarter front view from slightly below, imposing.` |
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
Completely original design, not resembling any existing film, series, game, comic or franchise. Imperial faction ships of a tyrannical empire: cold, severe, minimalist and intimidating vessels sharing one unified design language across the whole fleet: hard angular faceted hulls made of long rectangular monolithic blocks with sharp chamfered edges and crisp right angles, absolutely no soft rounded or organic curves and no bulbous shapes, perfectly flat dark panels with razor-thin seams, one continuous blade-like dorsal spine, rectangular engine blocks with narrow slit nozzles, thin cold steel-blue light slits, a dark gunmetal and graphite grey finish with matte black accents, symmetrical, immaculate, no ornament. Angular means rectangular faceted blocks, not an arrow or wedge silhouette. Avoid wedge-shaped or triangular hulls, avoid symmetrical twin-fin or X-shaped fighters, avoid white plastic armor with black visors, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
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
Completely original design, not resembling any existing film, series, game, comic or franchise. Imperial faction soldier of a tyrannical empire: a stern human trooper with a clearly visible bare face, cold hard expression, short cropped hair, wearing an angular flat-topped combat cap-helmet with a rigid chin guard that leaves the eyes, nose and mouth uncovered (not a full-face helmet, no dome-shaped helmet, no lens eyes). Body armor made of hard angular faceted plates in dark graphite and matte black, a high stiff collar, sharp shoulder blades, thin steel-blue light slits along the plates, a long dark tactical coat-free bodysuit underneath, heavy boots, a compact angular futuristic carbine with an integrated scope. The whole outfit is dark and menacing, absolutely not white, not pale, not glossy. Must not resemble the stormtroopers or helmeted troopers of any famous film or game. Strongly futuristic far-future science-fiction infantry look. Absolutely not a 20th-century or World War II look: no steel pot helmet, no wooden rifle stock, no canvas webbing, no trench coat, no historical uniform. Avoid wedge-shaped or triangular shapes, avoid white armor, avoid black full-face visors and glossy lens eyes, avoid robes, capes and glowing swords, avoid glowing red or green laser bolts, avoid any recognizable logo or emblem.
```

| Fichier | Prompt |
|---|---|
| `vaisseau-pirates-chasseur` | `A compact single-seat combat craft with a small cockpit bubble, a cluster of small engines at the rear and one cannon under the nose, small enough to be carried inside a larger ship. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-reconnaissance` | `A small reconnaissance craft: a thin central boom carrying a large sensor dish, folding solar panel wings, a small body and a tiny thruster block. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-transport_perso` | `A compact personal transport capsule for two passengers: a rounded body with a row of small portholes, a short docking collar on the nose, a single large engine bell at the rear. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-fregate` | `A medium military frigate about 150 meters long: a long hull with turret blisters, a forward crew module, a visible open hangar bay for small fighters, a troop hatch and a rear engine block. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-barge` | `A heavy landing craft shaped like a flying cargo container with a blunt rounded nose, a large drop ramp on the underside, four thick landing legs and a visible troop bay with bench seats. Three-quarter front view, slightly from above. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-capital` | `A colossal capital ship hundreds of meters long: a very long main hull with stacked habitat sections, several open hangar bays, dozens of turret blisters, huge radiator surfaces and a rear array of large engines. Three-quarter front view from slightly below, imposing. Pirate faction ships: improvised kitbashed vessels assembled from mismatched salvage, welded patchwork hull plates of different colors and ages, bolted-on scrap armor, exposed cables and hoses, tarps and crude hand-painted stripes (no letters), mismatched engines taken from different ships, rust, soot stains, jury-rigged antennas, very asymmetrical and chaotic but plausible.` |
| `vaisseau-pirates-restrictor` | `A heavy support warship about 250 meters long: a long main hull with an open ring frame around its middle carrying field emitter pylons topped by parabolic dishes of mismatched sizes salvaged from other ships, long mast antennas at the front, large radiator panels, a rear engine cluster, only a few small point-defense blisters. Three-quarter front view from slightly below, imposing.` |
| `vaisseau-pirates-troupe_assaut` | `A single soldier in a ready pose, wearing futuristic armor and carrying a futuristic carbine. Wide shot, camera pulled back: the complete full-length figure is entirely visible from the top of the helmet to the soles of both boots, both feet fully inside the frame, with a clear empty margin of magenta below the boots and on every side, nothing cropped. Three-quarter front view.` |
| `vaisseau-rebellion-chasseur` | `A compact single-seat combat craft with a small cockpit bubble, a cluster of small engines at the rear and one cannon under the nose, small enough to be carried inside a larger ship. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-reconnaissance` | `A small reconnaissance craft: a thin central boom carrying a large sensor dish, folding solar panel wings, a small body and a tiny thruster block. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-transport_perso` | `A compact personal transport capsule for two passengers: a rounded body with a row of small portholes, a short docking collar on the nose, a single large engine bell at the rear. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-fregate` | `A medium military frigate about 150 meters long: a long hull with turret blisters, a forward crew module, a visible open hangar bay for small fighters, a troop hatch and a rear engine block. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-barge` | `A heavy landing craft shaped like a flying cargo container with a blunt rounded nose, a large drop ramp on the underside, four thick landing legs and a visible troop bay with bench seats. Three-quarter front view, slightly from above. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-capital` | `A colossal capital ship hundreds of meters long: a very long main hull with stacked habitat sections, several open hangar bays, dozens of turret blisters, huge radiator surfaces and a rear array of large engines. Three-quarter front view from slightly below, imposing. Rebel faction ships: very old veteran vessels decades past their service life, faded and chipped paint in tired greens and creams, pitted and patched hull, visible replacement parts that do not match, rounded utilitarian industrial shapes, repaired dents, oil stains, cobbled-together upgrades, but clearly maintained with care.` |
| `vaisseau-rebellion-restrictor` | `A heavy support warship about 250 meters long: a long rounded utilitarian hull with an open ring frame around its middle carrying field emitter pylons topped by worn parabolic dishes, long mast antennas at the front, large radiator panels, a rear engine cluster, only a few small point-defense blisters. Three-quarter front view from slightly below, imposing.` |
| `vaisseau-rebellion-troupe_assaut` | `A single soldier in a ready pose, wearing futuristic armor and carrying a futuristic carbine. Wide shot, camera pulled back: the complete full-length figure is entirely visible from the top of the helmet to the soles of both boots, both feet fully inside the frame, with a clear empty margin of magenta below the boots and on every side, nothing cropped. Three-quarter front view.` |
| `vaisseau-empire-chasseur` | `A compact single-seat combat craft with a small angular cockpit canopy, a cluster of small engines at the rear and one cannon under the nose, small enough to be carried inside a larger ship. Three-quarter front view, slightly from above. Imperial faction ships of a tyrannical empire: cold, severe, minimalist and intimidating vessels sharing one unified design language across the whole fleet: hard angular faceted hulls made of long rectangular monolithic blocks with sharp chamfered edges and crisp right angles, absolutely no soft rounded or organic curves and no bulbous shapes, perfectly flat dark panels with razor-thin seams, one continuous blade-like dorsal spine, rectangular engine blocks with narrow slit nozzles, thin cold steel-blue light slits, a dark gunmetal and graphite grey finish with matte black accents, symmetrical, immaculate, no ornament. Angular means rectangular faceted blocks, not an arrow or wedge silhouette.` |
| `vaisseau-empire-reconnaissance` | `A small reconnaissance craft: a thin central boom carrying a large sensor dish, folding solar panel wings, a small body and a tiny thruster block. Three-quarter front view, slightly from above. Imperial faction ships of a tyrannical empire: cold, severe, minimalist and intimidating vessels sharing one unified design language across the whole fleet: hard angular faceted hulls made of long rectangular monolithic blocks with sharp chamfered edges and crisp right angles, absolutely no soft rounded or organic curves and no bulbous shapes, perfectly flat dark panels with razor-thin seams, one continuous blade-like dorsal spine, rectangular engine blocks with narrow slit nozzles, thin cold steel-blue light slits, a dark gunmetal and graphite grey finish with matte black accents, symmetrical, immaculate, no ornament. Angular means rectangular faceted blocks, not an arrow or wedge silhouette.` |
| `vaisseau-empire-transport_perso` | `A compact personal transport capsule for two passengers: an angular faceted body with a row of narrow rectangular viewports, a short docking collar on the nose, a single large rectangular engine block with a slit nozzle at the rear. Three-quarter front view, slightly from above. Imperial faction ships of a tyrannical empire: cold, severe, minimalist and intimidating vessels sharing one unified design language across the whole fleet: hard angular faceted hulls made of long rectangular monolithic blocks with sharp chamfered edges and crisp right angles, absolutely no soft rounded or organic curves and no bulbous shapes, perfectly flat dark panels with razor-thin seams, one continuous blade-like dorsal spine, rectangular engine blocks with narrow slit nozzles, thin cold steel-blue light slits, a dark gunmetal and graphite grey finish with matte black accents, symmetrical, immaculate, no ornament. Angular means rectangular faceted blocks, not an arrow or wedge silhouette.` |
| `vaisseau-empire-fregate` | `A medium military frigate about 150 meters long: a long hull with angular turret housings, a forward crew module, a visible open hangar bay for small fighters, a troop hatch and a rear engine block. Three-quarter front view, slightly from above. Imperial faction ships of a tyrannical empire: cold, severe, minimalist and intimidating vessels sharing one unified design language across the whole fleet: hard angular faceted hulls made of long rectangular monolithic blocks with sharp chamfered edges and crisp right angles, absolutely no soft rounded or organic curves and no bulbous shapes, perfectly flat dark panels with razor-thin seams, one continuous blade-like dorsal spine, rectangular engine blocks with narrow slit nozzles, thin cold steel-blue light slits, a dark gunmetal and graphite grey finish with matte black accents, symmetrical, immaculate, no ornament. Angular means rectangular faceted blocks, not an arrow or wedge silhouette.` |
| `vaisseau-empire-barge` | `A heavy landing craft shaped like a flying cargo container with a blunt flat chamfered nose, a large drop ramp on the underside, four thick landing legs and a visible troop bay with bench seats. Three-quarter front view, slightly from above. Imperial faction ships of a tyrannical empire: cold, severe, minimalist and intimidating vessels sharing one unified design language across the whole fleet: hard angular faceted hulls made of long rectangular monolithic blocks with sharp chamfered edges and crisp right angles, absolutely no soft rounded or organic curves and no bulbous shapes, perfectly flat dark panels with razor-thin seams, one continuous blade-like dorsal spine, rectangular engine blocks with narrow slit nozzles, thin cold steel-blue light slits, a dark gunmetal and graphite grey finish with matte black accents, symmetrical, immaculate, no ornament. Angular means rectangular faceted blocks, not an arrow or wedge silhouette.` |
| `vaisseau-empire-capital` | `A colossal capital ship hundreds of meters long: a very long main hull with stacked habitat sections, several open hangar bays, dozens of angular turret housings, huge radiator surfaces and a rear array of large engines. Three-quarter front view from slightly below, imposing. Imperial faction ships of a tyrannical empire: cold, severe, minimalist and intimidating vessels sharing one unified design language across the whole fleet: hard angular faceted hulls made of long rectangular monolithic blocks with sharp chamfered edges and crisp right angles, absolutely no soft rounded or organic curves and no bulbous shapes, perfectly flat dark panels with razor-thin seams, one continuous blade-like dorsal spine, rectangular engine blocks with narrow slit nozzles, thin cold steel-blue light slits, a dark gunmetal and graphite grey finish with matte black accents, symmetrical, immaculate, no ornament. Angular means rectangular faceted blocks, not an arrow or wedge silhouette.` |
| `vaisseau-empire-restrictor` | `A heavy support warship about 250 meters long: a very long rectangular faceted main hull with a rectangular angular ring frame around its middle carrying slab-shaped field emitter pylons topped by angular rectangular emitter panels, a long blade-like antenna mast at the front, large flat radiator surfaces, a rear array of rectangular engine blocks, only a few small angular point-defense housings. Three-quarter front view from slightly below, imposing.` |
| `vaisseau-empire-troupe_assaut` | `A single soldier in a ready pose, wearing futuristic armor and carrying a futuristic carbine. Wide shot, camera pulled back: the complete full-length figure is entirely visible from the top of the helmet to the soles of both boots, both feet fully inside the frame, with a clear empty margin of magenta below the boots and on every side, nothing cropped. Three-quarter front view.` |

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
| `combat-terrestre` | `Wide 16:9 cinematic ground assault at dusk: infantry in modular dark olive and sand-colored combat gear advancing from the open ramp of a heavy landing craft toward defensive fortifications, muzzle flashes and smoke, explosions throwing up dirt, dramatic orange and blue lighting, the action in the horizontal middle band.` |
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

## Icônes de l'interface : `icone-<clé>`

24 glyphes duotones affichés à 24 px dans la barre du haut, les titres, le menu et les panneaux. Ils doivent rester sobres et épurés, sans couleur, mais avec deux tons : une forme principale en blanc et une forme secondaire en gris foncé. Pour ces images, remplace le bloc STYLE et le bloc DESIGN ORIGINAL par le bloc DUOTONE ci-dessous, puis ajoute le bloc FOND MAGENTA et le prompt. Le script les met en 128 px dans `img/icone/<clé>.png` : le blanc devient opaque, le gris devient transparent à environ 35 %. Le jeu les affiche comme un masque : seules les deux intensités comptent, elles prennent la couleur du thème. Les images absentes ne cassent rien : le jeu garde l'icône SVG.

```
Minimalist duotone glyph icon, like a symbol from a duotone icon font: exactly two flat tones and nothing else, a primary shape in solid pure white (#FFFFFF) and a secondary shape in solid dark grey (#595959) that adds depth and detail to the same object. Flat fill, no outline, no gradient, no shading, no texture, no shadow, no color, no text, no letters, no logo. Very simple, bold and geometric, with large clear shapes and rounded corners; small clean cut-outs may show the background. Perfectly centered, sober and clean, designed to stay instantly readable when shrunk to 24 pixels. Completely original design.
```

| Fichier | Prompt |
|---|---|
| `icone-credits` | `A stack of three coins seen slightly from the side: the top coin in white with a simple ring cut out of its face, the two coins beneath it in dark grey.` |
| `icone-revenu` | `One coin in dark grey with a bold white arrow rising in front of it and pointing up and to the right.` |
| `icone-planetes` | `A round planet in white with a curved band cut out across it, and one thin tilted ring around it in dark grey.` |
| `icone-fusee` | `A small spaceship in white flying diagonally toward the upper right, its short flame trail and two short speed lines in dark grey.` |
| `icone-carte` | `A folded star chart in three panels: the middle panel in white, the two side panels in dark grey, with a dotted route line cut out of it ending in a small round marker.` |
| `icone-persos` | `Two head-and-shoulders human busts side by side: the front one in white, the one slightly behind it in dark grey, plain featureless faces.` |
| `icone-classement` | `A three-step winner podium: the tallest middle step in white, the two side steps in dark grey, and a five-pointed star in white floating above the middle step.` |
| `icone-mission` | `A flag in white on a short pole, planted on a small round mound in dark grey.` |
| `icone-bouclier` | `A classic heater-shaped shield in dark grey with one bold white vertical band through its center.` |
| `icone-canon` | `A short stubby cannon in white on a round base in dark grey, barrel pointing to the upper right, with a small flash burst at the muzzle in dark grey.` |
| `icone-revolte` | `A raised clenched fist in white with simple thick fingers, a dark grey cuff below the wrist.` |
| `icone-force` | `A flexed arm showing the biceps, the upper arm in white and the forearm in dark grey, seen from the side.` |
| `icone-endurance` | `A bold heart in white with a small pulse line across it in dark grey.` |
| `icone-diplomatie` | `A balance scale: the horizontal beam and the central post in white, the two round hanging pans in dark grey.` |
| `icone-emplacements` | `A two-by-two grid of four square tiles in dark grey, one of the tiles in white and topped with a small building shape.` |
| `icone-construire` | `A hammer in white and a wrench in dark grey crossed in an X.` |
| `icone-flotte` | `Two small identical spaceships seen from above, placed side by side with their noses pointing up: the left one in white, the right one in dark grey.` |
| `icone-route` | `A short dotted path in dark grey curving from a small round start dot to a bold white arrowhead pointing right.` |
| `icone-actions` | `A single bold lightning bolt in white, with a larger dark grey bolt shape slightly offset behind it.` |
| `icone-quartier` | `A compact command building with a flat roof in white and a row of window cut-outs, its small satellite dish and short antenna on top in dark grey.` |
| `icone-journal` | `A closed notebook in white seen from the front with two horizontal lines cut out of the cover, a ribbon bookmark hanging out of the bottom in dark grey.` |
| `icone-trophee` | `A trophy cup in white, its two curved handles and its small base in dark grey.` |
| `icone-connexion` | `An open doorway in a wall in dark grey, with a bold white arrow pointing into it from the left.` |
| `icone-inscription` | `A head-and-shoulders human bust in white with a small round badge in dark grey at its lower right, holding a bold white plus sign.` |

## Icônes d'action : `icone-<clé>`

12 glyphes pour les boutons d'action (page Flottes, panneaux de combat, menace pirate). Même bloc DUOTONE que ci-dessus, puis le prompt. Ils s'affichent de 24 px (vaisseau) à 32 px (flotte). « Carte » existe déjà (`icone-carte`).

| Fichier | Prompt |
|---|---|
| `icone-voyager` | `A bold white arrow pointing to the upper right, leaving a short dotted trail behind it in dark grey that starts from a small round dark grey dot.` |
| `icone-dissoudre` | `Two small identical spaceships seen from above, noses pointing up, moving apart: the left one in white tilted toward the upper left, the right one in dark grey tilted toward the upper right, with a short dotted dark grey trail behind each, starting from the same central point.` |
| `icone-renommer` | `A white pencil tilted at 45 degrees writing on a dark grey rectangular name tag, with two short horizontal text bars cut out of the tag and the pencil tip touching the second bar.` |
| `icone-embarquer` | `A dark grey spaceship seen from the side with an open hatch in its flank, and a small white human figure walking into the hatch, with a short bold white arrow pointing from the figure into the ship.` |
| `icone-decharger` | `A dark grey spaceship seen from the side with an open hatch in its flank, and a small white human figure walking out of the hatch, with a short bold white arrow pointing from the ship to the figure.` |
| `icone-chercher` | `A white magnifying glass whose lens is a thick ring containing a small dark grey ringed planet and two tiny dark grey stars around it, the short handle in dark grey at the lower right.` |
| `icone-reconnaitre` | `A small white scout spaceship seen from above with a dark grey radar scanning beam fanning out in a wide triangle in front of it, with two curved scan arcs cut out of the beam.` |
| `icone-attaque` | `A white missile with a pointed nose flying to the right, with three short dark grey flame streaks behind it, about to hit a large jagged star-shaped dark grey explosion burst on the right.` |
| `icone-attaque_flotte` | `Two white missiles with pointed noses flying side by side to the right, one above the other, both aimed at one shared large jagged star-shaped dark grey explosion burst on the right.` |
| `icone-debarquement` | `A dark grey landing craft seen from the side hovering above a flat ground line, with a small white soldier figure and a bold white arrow descending from its belly toward the ground line.` |
| `icone-combattre` | `Two crossed futuristic blaster pistols forming an X, chunky rounded sci-fi design with a thick barrel, a glowing energy cell and a short grip, one pistol in white and one in dark grey, muzzles at the top.` |
| `icone-laisser` | `An open white hand held palm up with fingers slightly spread, a single dark grey coin falling away from it with a short dotted trail above the coin.` |

## Icônes d'action, seconde série : `icone-<clé>`

8 glyphes pour les boutons des persos, de la planète, du rapport de combat et de la création de flotte. Même bloc DUOTONE, plus la phrase « The magenta background must fill the entire image to all four edges: no frame, no border, no margin, no white edge, no lighter band. » juste avant le sujet.

| Fichier | Prompt |
|---|---|
| `icone-negocier` | `Two hands clasped in a handshake seen from the side: the left hand in white and the right hand in dark grey, joined at the center, simple bold shapes.` |
| `icone-apaiser` | `A white dove in flight seen from the side with spread wings, carrying a small dark grey olive branch in its beak.` |
| `icone-demolir` | `A dark grey building with a flat roof and window cut-outs, struck from the upper right by a white wrecking ball hanging on a chain, with three small dark grey debris pieces falling from the impact.` |
| `icone-recruter` | `A white soldier head-and-shoulders bust wearing a plain dark grey combat helmet with a visor and a chin strap, standing in front of two smaller dark grey soldier busts behind it, like a small squad. No star, no emblem, no insignia on the helmet, no plus sign, no cross.` |
| `icone-produire` | `A dark grey factory building with a flat roof and two tall smokestacks, and in front of it a white conveyor belt carrying one small white spaceship.` |
| `icone-resoudre` | `A bold white play triangle pointing right, in front of a large dark grey ring-shaped circle.` |
| `icone-passer` | `Two bold white play triangles pointing right side by side, followed at the right by a thick dark grey vertical bar, like a skip-forward symbol.` |
| `icone-creer_flotte` | `Two small identical spaceships seen from above, side by side with their noses pointing up, the left one in white and the right one in dark grey, with a bold white plus sign at the upper right.` |

## Illustrations d'événements : `evenement-<clé>` (fonds plein cadre, 16:9)

3 images pour les fenêtres « une planète rejoint votre cause », « une planète se révolte » et « une planète conquise par la force ». Mêmes blocs STYLE et DESIGN ORIGINAL que les fonds, sans magenta, sans emblème de camp (le clan est affiché par le jeu).

| Fichier | Prompt |
|---|---|
| `evenement-rejoint` | `Wide 16:9 cinematic scene of a planet joining a cause: a crowd of settlers and workers in utilitarian jumpsuits cheering in front of a landing plaza of an industrial colony, a freshly raised banner with no symbol on a tall mast, a colonial governor shaking hands with a delegate, warm golden morning light, the planet's curved horizon and a tall orbital elevator in the background, the action in the horizontal middle band.` |
| `evenement-revolte` | `Wide 16:9 cinematic scene of a planet in revolt: an angry crowd of colonists in worn work clothes filling the street of an industrial colony, torn down banner on the ground, burning barricades made of cargo containers, thick smoke, orange fire light against a dark blue dusk sky, the empty garrison gate behind them, the action in the horizontal middle band.` |
| `evenement-conquise` | `Wide 16:9 cinematic scene of a planet conquered by force: armored infantry in modular dark olive and sand-colored combat gear holding the main plaza of a colony after the assault, a landing craft on the ground behind them, a pole with an unmarked flag being planted, smoke rising from damaged buildings, defeated defenders disarmed on the side, dramatic orange and blue lighting at dusk, the action in the horizontal middle band.` |

## Icônes de structures : `icone-<clé>`

5 glyphes pour les structures des planètes (liste « Construire », emplacements, titres des usines). Même bloc DUOTONE, plus la phrase « The magenta background must fill the entire image to all four edges: no frame, no border, no margin, no white edge, no lighter band. » juste avant le sujet. Bouclier planétaire et canon orbital réutilisent `icone-bouclier` et `icone-canon`.

| Fichier | Prompt |
|---|---|
| `icone-usine_legere` | `A small dark grey orbital station dock module: a short cylindrical hull with a round docking port, one small solar panel wing and a mast antenna with a small dish. Docked at its port, a tiny white one-seat spacecraft: a cylindrical fuel tank body, a small bubble-shaped cockpit module offset on one side, one cannon on a short boom, and a rear cluster of two small engine nozzles. Strictly science fiction and futuristic: no smokestacks, no crane, no factory chimney, no airplane, no airliner windows, no space shuttle, no boat, no sea vessel, no sails, no water.` |
| `icone-usine_moyenne` | `A medium dark grey orbital shipyard: two cylindrical hull modules joined by an exposed truss frame, with a large ring-shaped docking arm. Held inside the truss, a white medium spacecraft: a long cylindrical spine carrying two spherical fuel tanks, two large rectangular radiator panels on the sides, a small forward crew module and a cluster of three large engine nozzles at the rear. Strictly science fiction and futuristic: no smokestacks, no crane, no factory chimney, no airplane, no airliner windows, no space shuttle, no boat, no sea vessel, no sails, no water.` |
| `icone-usine_lourde` | `A very large dark grey orbital drydock: a huge open truss cradle with several stacked ring sections and big rectangular radiator panels. Held inside the cradle, a gigantic white capital spacecraft: a very long cylindrical spine, stacked habitat rings, a row of small turret blisters along the hull and a wide array of engine nozzles at the rear. Strictly science fiction and futuristic: no smokestacks, no crane, no factory chimney, no airplane, no airliner windows, no space shuttle, no boat, no sea vessel, no sails, no water.` |
| `icone-ferme` | `A dark grey hydroponic orbital station module: a cylindrical tube with a transparent dome section showing three rows of white plants growing, one small solar panel wing on the side and a short mast antenna. No animals, no tractor, no barn. Strictly science fiction and futuristic.` |
| `icone-academie` | `A dark grey round orbital station building with a domed roof, a large radar dish and a tall antenna mast, and in front of its entrance a white holographic open book floating above a small dark grey circular platform. No columns, no pediment, no star, no emblem, no flag. Strictly science fiction and futuristic.` |

## Illustrations d'événements d'exploration : `evenement-<clé>` (fonds plein cadre, 16:9)

4 images pour les fenêtres « Système découvert », « Rien trouvé », « Signal perdu » et « Planète analysée ». Mêmes blocs STYLE et DESIGN ORIGINAL que les fonds, sans magenta ni emblème.

| Fichier | Prompt |
|---|---|
| `evenement-decouverte` | `Wide 16:9 cinematic view from just behind a small reconnaissance spacecraft (a long thin sensor boom with a folded dish, two solar panel wings, a small cylindrical body) as it emerges from darkness toward a newly discovered star system: a small bright orange star and four planets of different sizes and colors lined up in the distance, thin scanning beams sweeping across them, a faint glow of dust around the star, the system in the horizontal middle band.` |
| `evenement-rien` | `Wide 16:9 cinematic scene of a small reconnaissance spacecraft (a long thin sensor boom with a dish, two solar panel wings, a small cylindrical body) drifting alone in an empty dark region of deep space, only faint distant stars and a thin cloud of cosmic dust around it, its dish pointed at nothing, a single thin scanner beam fading into the darkness, cold blue light, the craft in the horizontal middle band.` |
| `evenement-perdu` | `Wide 16:9 cinematic scene of a lost reconnaissance spacecraft: a small wrecked craft tumbling beside a jagged asteroid field, one solar panel torn off, its sensor dish bent and broken, sparks and venting gas, a faint red distress light blinking on its hull, deep dark space, the craft in the horizontal middle band.` |
| `evenement-analyse` | `Wide 16:9 cinematic scene of a planet being analyzed: a small reconnaissance spacecraft in high orbit above the night side of a planet, projecting a thin green-blue grid beam onto it, the planet surface showing scattered city lights, a few orbital defense platforms and small warships in orbit each outlined by thin light brackets, the action in the horizontal middle band.` |

## Textures de planètes telluriques : `planete-<n>` (2:1, raccord automatique)

12 nouvelles textures de surface (27 à 38) : projection plane 2:1 d'un monde rocheux entier, sans sphère ni halo ni étoiles. Sans image de référence (ça consomme des jetons) : coller seulement le prompt. La colonne « Référence » ne sert qu'en cas de rendu raté. Le script `tools/detourer-magenta.py` raccorde le bord gauche au bord droit (fondu) pour que le défilement n'ait aucune couture, puis range le résultat dans `img/planete/<n>.jpg`.

Début de chaque prompt (bloc TEXTURE) : `Flat equirectangular surface texture map of an entire rocky planet, exact 2:1 aspect ratio, seen straight on as a flat rectangular map with no sphere, no curvature, no atmosphere glow, no stars, no clouds shadow, no text, no labels, no grid lines. Flat, realistic global satellite-map look with fine surface detail, like a real planetary mosaic map.`

| Fichier | Référence à joindre | Prompt (après le bloc TEXTURE) |
|---|---|---|
| `planete-27` | `13.jpg` | `A deep blue ocean world: a global dark-blue ocean with a few small scattered green and brown islands and thin white cloud swirls, subtle lighter shallow-water rings around the islands.` |
| `planete-28` | `22.jpg` | `A golden desert world: huge sweeping dunes in ochre and gold with long wind-carved ridges, dark rocky outcrops, and a few pale dry salt basins, no water.` |
| `planete-29` | `19.jpg` | `A dense jungle world: continents covered in deep green forest canopy with darker river valleys, small blue lakes, brown mountain ranges and thin white mist, surrounded by a dark teal ocean.` |
| `planete-30` | `13.jpg` | `An archipelago world: hundreds of small and medium green and sand-colored islands grouped in chains over a bright turquoise and deep blue shallow sea, a few white cloud bands.` |
| `planete-31` | `15.jpg` | `A tundra world: grey-brown frozen plains with pale green moss patches, white snow-covered mountain ranges, frozen lakes and long glacial rivers, a thin white polar cap at top and bottom.` |
| `planete-32` | `25.jpg` | `A red canyon world: a vast network of deep rust-red and orange canyons and mesas, layered sandstone cliffs with pale strata, a few dark dust fans, no water.` |
| `planete-33` | `26.jpg` | `A salt flat world: enormous pale white and cream salt plains with cracked polygon patterns, a few turquoise mineral lakes and brown rocky ridges.` |
| `planete-34` | `17.jpg` | `A dark volcanic world: black basalt plains crossed by networks of glowing orange lava rivers, several large dark volcano calderas with red rims, scattered grey ash plumes.` |
| `planete-35` | `24.jpg` | `An ash and fissure world: charcoal grey and dark brown ash plains split by long pale cracks and rifts, scattered rust-colored rocky patches and a few pale dust streaks.` |
| `planete-36` | `21.jpg` | `A violet mineral world: purple, magenta and deep blue crystalline rock plains with lighter lilac ridges and veins, glittering mineral deposits, no vegetation, no water.` |
| `planete-37` | `19.jpg` | `A green mineral world: emerald and olive green copper-oxide rock plains with teal veins, lighter green ridges, dark crater fields and yellow sulfur patches, no vegetation, no water.` |
| `planete-38` | `10.jpg` | `A dark cratered world: charcoal and dark brown rock covered in overlapping impact craters of all sizes, bright fresh-ejecta rays around the youngest craters, subtle grey dust plains, no atmosphere.` |
| `planete-39` | (aucune) | `A hyper-industrialized city planet: the entire surface is one continuous megacity, dense grids of dark grey and brown industrial districts, endless factory complexes with rows of smokestacks, refinery tank farms, rail and highway networks, glowing orange and amber city lights, rivers of glowing orange molten slag, large smog and pollution haze patches, no vegetation, no oceans.` |
