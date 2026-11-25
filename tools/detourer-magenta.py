#!/usr/bin/env python3
"""Usage: detourer-magenta.py <folder of generated images> [game img folder].
Names and prompts: tools/prompts-images.md."""
import os
import sys

import numpy
from PIL import Image

OBJETS = ('vaisseau', 'batiment', 'role', 'bonus')
FONDS = {'camp-pirates': 'camp/pirates', 'camp-empire': 'camp/empire', 'camp-rebellion': 'camp/rebellion', 'carte': 'carte', 'accueil': 'accueil', 'combat': 'combat'}
FONDS.update({'page-' + page: 'page/' + page for page in ('tableauBord', 'listePlanete', 'listeVaisseau', 'listePerso', 'classement', 'listeCamp', 'inscription', 'connection')})
EXTENSIONS = ('.png', '.jpg', '.jpeg', '.webp')
COTE_OBJET = 512
MARGE_OBJET = 24
LARGEUR_FOND = 1600
QUALITE_JPEG = 82
TAILLE_COIN = 8
DISTANCE_TRANSPARENTE = 30.0
DISTANCE_OPAQUE = 200.0
MAGENTA_ROUGE_BLEU_MIN = 170
MAGENTA_VERT_MAX = 110
SEUIL_OPACITE_CADRAGE = 0.1


def couleur_du_fond(pixels):
    coins = numpy.concatenate([
        pixels[:TAILLE_COIN, :TAILLE_COIN].reshape(-1, 3),
        pixels[:TAILLE_COIN, -TAILLE_COIN:].reshape(-1, 3),
        pixels[-TAILLE_COIN:, :TAILLE_COIN].reshape(-1, 3),
        pixels[-TAILLE_COIN:, -TAILLE_COIN:].reshape(-1, 3),
    ])
    fond = numpy.median(coins, axis=0)
    if fond[0] < MAGENTA_ROUGE_BLEU_MIN or fond[2] < MAGENTA_ROUGE_BLEU_MIN or fond[1] > MAGENTA_VERT_MAX:
        return None
    return fond


def detourer(image):
    pixels = numpy.asarray(image.convert('RGB')).astype(numpy.float64)
    fond = couleur_du_fond(pixels)
    if fond is None:
        return None
    distance = numpy.sqrt(((pixels - fond) ** 2).sum(axis=2))
    opacite = numpy.clip((distance - DISTANCE_TRANSPARENTE) / (DISTANCE_OPAQUE - DISTANCE_TRANSPARENTE), 0.0, 1.0)
    teinte = numpy.clip(numpy.minimum(pixels[:, :, 0], pixels[:, :, 2]) - pixels[:, :, 1], 0, None)
    couleurs = pixels.copy()
    couleurs[:, :, 0] -= teinte
    couleurs[:, :, 2] -= teinte
    resultat = numpy.dstack([numpy.clip(couleurs, 0, 255), opacite * 255]).astype(numpy.uint8)
    return Image.fromarray(resultat, 'RGBA')


def cadrer(image):
    opacite = numpy.asarray(image)[:, :, 3] / 255.0
    lignes = numpy.where(opacite.max(axis=1) > SEUIL_OPACITE_CADRAGE)[0]
    colonnes = numpy.where(opacite.max(axis=0) > SEUIL_OPACITE_CADRAGE)[0]
    image = image.crop((colonnes[0], lignes[0], colonnes[-1] + 1, lignes[-1] + 1))
    utile = COTE_OBJET - 2 * MARGE_OBJET
    echelle = utile / max(image.size)
    image = image.resize((max(1, round(image.width * echelle)), max(1, round(image.height * echelle))), Image.LANCZOS)
    carre = Image.new('RGBA', (COTE_OBJET, COTE_OBJET), (0, 0, 0, 0))
    carre.paste(image, ((COTE_OBJET - image.width) // 2, (COTE_OBJET - image.height) // 2))
    return carre


def traiter(chemin, nom, destination):
    base = nom.rsplit('.', 1)[0]
    dossier, _, cle = base.partition('-')
    if dossier in OBJETS and cle != '':
        image = detourer(Image.open(chemin))
        if image is None:
            print('  ignoré %s : le fond n\'est pas un magenta uni dans les 4 coins' % nom)
            return False
        sortie = os.path.join(destination, dossier, cle + '.png')
        os.makedirs(os.path.dirname(sortie), exist_ok=True)
        cadrer(image).save(sortie, optimize=True)
        print('  %s -> %s' % (nom, sortie))
        return True
    if base in FONDS:
        image = Image.open(chemin).convert('RGB')
        image = image.resize((LARGEUR_FOND, round(image.height * LARGEUR_FOND / image.width)), Image.LANCZOS)
        sortie = os.path.join(destination, FONDS[base] + '.jpg')
        os.makedirs(os.path.dirname(sortie), exist_ok=True)
        image.save(sortie, quality=QUALITE_JPEG, optimize=True)
        print('  %s -> %s' % (nom, sortie))
        return True
    print('  ignoré %s : nom non reconnu' % nom)
    return False


def main():
    if len(sys.argv) < 2:
        sys.exit(__doc__)
    source = sys.argv[1]
    destination = sys.argv[2] if len(sys.argv) > 2 else os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', 'img')
    faits = 0
    refuses = 0
    for nom in sorted(os.listdir(source)):
        if not nom.lower().endswith(EXTENSIONS):
            continue
        if traiter(os.path.join(source, nom), nom, destination):
            faits += 1
        else:
            refuses += 1
    print('%d image(s) préparée(s), %d ignorée(s)' % (faits, refuses))


main()
