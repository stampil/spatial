#!/usr/bin/env python3
"""Usage: generer-terre.py [output folder].
Generates the earth-like planet textures (img/planete/40.jpg to 45.jpg) from invented continents: 3D noise sampled on a sphere, so the map wraps horizontally with no seam and never copies the real Earth. Water, sand, forest, desert, mountains and snow follow altitude, latitude and moisture."""
import os
import sys

import numpy
from PIL import Image

LARGEUR = 1440
HAUTEUR = 720
TAILLE_RESEAU = 64
OCTAVES = 7
PERSISTANCE = 0.5
ECART_FREQUENCE = 2.0
DECALAGE_POSITIF = 17.0
QUALITE_JPEG = 90
SEUIL_COUTURE = 2.0
LATITUDE_CALOTTE = 73.0
FLOU_CALOTTE = 5.0
LATITUDE_DESERT = 25.0
LARGEUR_DESERT = 10.0
FORCE_DESERT = 0.3
FORCE_EQUATEUR_HUMIDE = 0.18
FORCE_RELIEF = 2.6
OMBRE_MINIMUM = 0.55
OMBRE_MAXIMUM = 1.2
LUMIERE = (-0.6, -0.8)
RAYON_PLAGE = 0.02
FORCE_PLAGE = 0.55
OPACITE_NUAGES = 0.4

OCEAN_PROFOND = numpy.array([0.02, 0.09, 0.27])
OCEAN_CLAIR = numpy.array([0.13, 0.52, 0.62])
SABLE = numpy.array([0.86, 0.77, 0.52])
SAVANE = numpy.array([0.62, 0.6, 0.33])
HERBE = numpy.array([0.34, 0.55, 0.22])
FORET = numpy.array([0.1, 0.36, 0.14])
CONIFERE = numpy.array([0.11, 0.29, 0.2])
TOUNDRA = numpy.array([0.5, 0.5, 0.43])
ROCHE = numpy.array([0.43, 0.38, 0.34])
NEIGE = numpy.array([0.95, 0.97, 1.0])

MONDES = (
    {'numero': 40, 'graine': 4011, 'terres': 0.3, 'frequence': 0.9, 'nord': 0.35, 'equateur': 0.0, 'froid': 0.0, 'aridite': 0.0, 'nuages': 0.5},
    {'numero': 41, 'graine': 4177, 'terres': 0.33, 'frequence': 1.7, 'nord': 0.0, 'equateur': 0.0, 'froid': 0.0, 'aridite': 0.05, 'nuages': 0.55},
    {'numero': 42, 'graine': 4293, 'terres': 0.14, 'frequence': 2.6, 'nord': 0.0, 'equateur': 0.1, 'froid': 0.05, 'aridite': -0.05, 'nuages': 0.6},
    {'numero': 43, 'graine': 4351, 'terres': 0.38, 'frequence': 1.3, 'nord': 0.0, 'equateur': 0.55, 'froid': 0.0, 'aridite': -0.05, 'nuages': 0.5},
    {'numero': 44, 'graine': 4466, 'terres': 0.35, 'frequence': 1.4, 'nord': 0.15, 'equateur': 0.0, 'froid': 0.28, 'aridite': -0.1, 'nuages': 0.45},
    {'numero': 45, 'graine': 4589, 'terres': 0.33, 'frequence': 1.2, 'nord': 0.0, 'equateur': 0.0, 'froid': -0.05, 'aridite': 0.3, 'nuages': 0.3},
)


def lisser(valeur):
    return valeur * valeur * (3.0 - 2.0 * valeur)


def bruit(points, frequence, graine):
    reseau = numpy.random.default_rng(graine).random((TAILLE_RESEAU, TAILLE_RESEAU, TAILLE_RESEAU))
    position = points * frequence + DECALAGE_POSITIF
    entier = numpy.floor(position).astype(int)
    reste = lisser(position - entier)
    bas = entier % TAILLE_RESEAU
    haut = (entier + 1) % TAILLE_RESEAU
    resultat = 0.0
    for choix_x in (0, 1):
        for choix_y in (0, 1):
            for choix_z in (0, 1):
                x = haut[..., 0] if choix_x else bas[..., 0]
                y = haut[..., 1] if choix_y else bas[..., 1]
                z = haut[..., 2] if choix_z else bas[..., 2]
                poids = (reste[..., 0] if choix_x else 1 - reste[..., 0]) * (reste[..., 1] if choix_y else 1 - reste[..., 1]) * (reste[..., 2] if choix_z else 1 - reste[..., 2])
                resultat = resultat + reseau[x, y, z] * poids
    return resultat


def fractal(points, frequence, graine):
    total = 0.0
    amplitude = 1.0
    somme = 0.0
    for octave in range(OCTAVES):
        total = total + amplitude * bruit(points, frequence * ECART_FREQUENCE ** octave, graine + octave * 101)
        somme += amplitude
        amplitude *= PERSISTANCE
    return total / somme


def points_sur_sphere():
    longitude = (numpy.arange(LARGEUR) + 0.5) / LARGEUR * 2 * numpy.pi - numpy.pi
    latitude = numpy.pi / 2 - (numpy.arange(HAUTEUR) + 0.5) / HAUTEUR * numpy.pi
    lon, lat = numpy.meshgrid(longitude, latitude)
    points = numpy.stack([numpy.cos(lat) * numpy.cos(lon), numpy.cos(lat) * numpy.sin(lon), numpy.sin(lat)], axis=-1)
    return points, numpy.degrees(lat)


def quantile_pondere(valeurs, poids, part):
    ordre = numpy.argsort(valeurs.ravel())
    cumul = numpy.cumsum(poids.ravel()[ordre])
    cumul /= cumul[-1]
    return valeurs.ravel()[ordre][numpy.searchsorted(cumul, part)]


def rampe(valeurs, arrets):
    resultat = numpy.zeros(valeurs.shape + (3,))
    positions = [arret[0] for arret in arrets]
    for canal in range(3):
        resultat[..., canal] = numpy.interp(valeurs, positions, [arret[1][canal] for arret in arrets])
    return resultat


def melange(a, b, poids):
    return a * (1 - poids[..., None]) + b * poids[..., None]


def etape(valeurs, debut, fin):
    return lisser(numpy.clip((valeurs - debut) / (fin - debut), 0.0, 1.0))


def monde(parametres):
    graine = parametres['graine']
    points, latitude = points_sur_sphere()
    deformation = numpy.stack([fractal(points, 1.5, graine + 7 * axe) - 0.5 for axe in range(3)], axis=-1)
    points_deformes = points + 0.35 * deformation
    altitude = fractal(points_deformes, parametres['frequence'], graine)
    altitude = altitude + parametres['nord'] * numpy.sin(numpy.radians(latitude)) + parametres['equateur'] * numpy.cos(numpy.radians(latitude)) ** 2
    poids_surface = numpy.cos(numpy.radians(latitude))
    mer = quantile_pondere(altitude, poids_surface, 1.0 - parametres['terres'])
    relief = altitude - mer
    sur_terre = relief > 0
    haut_terre = numpy.where(sur_terre, relief / max(relief.max(), 1e-6), 0.0)
    fond = numpy.where(sur_terre, 0.0, relief / min(relief.min(), -1e-6))

    bruit_temperature = fractal(points, 2.0, graine + 900) - 0.5
    temperature = numpy.cos(numpy.radians(latitude)) ** 0.9 - 0.55 * haut_terre - parametres['froid'] + 0.2 * bruit_temperature
    humidite = fractal(points, 2.5, graine + 1700) * 1.3 - 0.15 - parametres['aridite']
    humidite -= FORCE_DESERT * numpy.exp(-((numpy.abs(latitude) - LATITUDE_DESERT) / LARGEUR_DESERT) ** 2)
    humidite += FORCE_EQUATEUR_HUMIDE * numpy.exp(-(latitude / 12.0) ** 2)
    humidite = numpy.clip(humidite, 0.0, 1.0)

    chaud = rampe(humidite, ((0.0, SABLE), (0.3, SAVANE), (0.52, HERBE), (0.8, FORET)))
    froid = rampe(humidite, ((0.0, TOUNDRA), (0.5, CONIFERE), (1.0, CONIFERE)))
    terre = melange(froid, chaud, etape(temperature, 0.35, 0.6))
    terre = melange(terre, ROCHE, etape(haut_terre, 0.35, 0.7))
    terre = melange(terre, NEIGE, numpy.maximum(etape(-temperature, -0.28, -0.12), etape(haut_terre, 0.78, 0.95) * etape(-temperature, -0.95, -0.3)))
    terre = melange(terre, SABLE, (1.0 - etape(haut_terre, 0.0, RAYON_PLAGE)) * FORCE_PLAGE)

    mer_couleur = rampe(numpy.clip(fond, 0.0, 1.0) ** 0.55, ((0.0, OCEAN_CLAIR), (1.0, OCEAN_PROFOND)))
    couleurs = numpy.where(sur_terre[..., None], terre, mer_couleur)

    bruit_calotte = fractal(points, 4.0, graine + 2300) - 0.5
    calotte = etape(numpy.abs(latitude) + FLOU_CALOTTE * bruit_calotte * 2, LATITUDE_CALOTTE, LATITUDE_CALOTTE + 4.0)
    couleurs = melange(couleurs, NEIGE, calotte)

    gradient_y, gradient_x = numpy.gradient(altitude)
    gradient_x = gradient_x / numpy.maximum(numpy.cos(numpy.radians(latitude)), 0.15)
    ombre = 1.0 + FORCE_RELIEF * LARGEUR * 0.012 * (gradient_x * LUMIERE[0] + gradient_y * LUMIERE[1])
    ombre = numpy.where(sur_terre, numpy.clip(ombre, OMBRE_MINIMUM, OMBRE_MAXIMUM), 1.0)
    couleurs = couleurs * ombre[..., None]

    nuages = numpy.clip((fractal(points * numpy.array([1.0, 1.0, 2.2]), 3.0, graine + 3100) - 0.6) * 3.5, 0.0, 1.0) * parametres['nuages']
    couleurs = melange(couleurs, numpy.ones(3) * 0.97, nuages * OPACITE_NUAGES / 0.55)
    return Image.fromarray((numpy.clip(couleurs, 0.0, 1.0) * 255).astype(numpy.uint8))


def rapport_couture(image):
    pixels = numpy.array(image.convert('RGB')).astype(float)
    couture = numpy.abs(pixels[:, 0] - pixels[:, -1]).mean()
    voisin = numpy.abs(pixels[:, 1:] - pixels[:, :-1]).mean()
    return couture / max(voisin, 0.1)


if __name__ == '__main__':
    destination = sys.argv[1] if len(sys.argv) > 1 else os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'img', 'planete')
    os.makedirs(destination, exist_ok=True)
    for parametres in MONDES:
        image = monde(parametres)
        rapport = rapport_couture(image)
        sortie = os.path.join(destination, '%d.jpg' % parametres['numero'])
        image.save(sortie, quality=QUALITE_JPEG)
        print(sortie, 'couture', round(rapport, 2), 'OK' if rapport <= SEUIL_COUTURE else 'COUTURE VISIBLE')
