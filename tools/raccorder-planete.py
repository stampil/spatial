#!/usr/bin/env python3
"""Usage: raccorder-planete.py <image> [<image>...].
Makes a planet texture tile horizontally: the end of the image is faded into its start over a band, so the last column continues into the first one. The image is rewritten in place at the same size."""
import sys

import numpy
from PIL import Image

LARGEUR = 1440
HAUTEUR = 720
PART_BANDE = 0.15
QUALITE_JPEG = 90
SEUIL_COUTURE = 2.0


def raccorder(image):
    base = image.convert('RGB').resize((LARGEUR, HAUTEUR), Image.LANCZOS)
    pixels = numpy.array(base).astype(float)
    bande = int(LARGEUR * PART_BANDE)
    largeur_utile = LARGEUR - bande
    poids = numpy.linspace(0.0, 1.0, bande).reshape(1, bande, 1)
    debut = pixels[:, :bande]
    fin = pixels[:, largeur_utile:]
    fondu = fin * (1.0 - poids) + debut * poids
    resultat = numpy.concatenate([fondu, pixels[:, bande:largeur_utile]], axis=1)
    return Image.fromarray(resultat.round().astype('uint8')).resize((LARGEUR, HAUTEUR), Image.LANCZOS)


def rapport_couture(image):
    pixels = numpy.array(image.convert('RGB')).astype(float)
    couture = numpy.abs(pixels[:, 0] - pixels[:, -1]).mean()
    voisin = numpy.abs(pixels[:, 1:] - pixels[:, :-1]).mean()
    return couture / max(voisin, 0.1)


if __name__ == '__main__':
    for chemin in sys.argv[1:]:
        raccorde = raccorder(Image.open(chemin))
        raccorde.save(chemin, quality=QUALITE_JPEG)
        rapport = rapport_couture(raccorde)
        print(chemin, 'rapport couture', round(rapport, 2), 'OK' if rapport <= SEUIL_COUTURE else 'COUTURE VISIBLE')
