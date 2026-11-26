#!/usr/bin/env python3
"""Usage: detourer-magenta.py <folder of generated images> [game img folder].
Names and prompts: tools/prompts-images.md. An icone- image is turned into a white mask whose grey tone becomes partial transparency. A name like vaisseau-pirates-chasseur goes to img/vaisseau/pirates/chasseur.png."""
import importlib.util
import os
import sys

import numpy
from PIL import Image

OBJETS = ('vaisseau', 'batiment', 'role', 'bonus', 'logo', 'icone')
FONDS = {'camp-pirates': 'camp/pirates', 'camp-empire': 'camp/empire', 'camp-rebellion': 'camp/rebellion', 'carte': 'carte', 'accueil': 'accueil', 'combat': 'combat', 'combat-terrestre': 'combat-terrestre', 'evenement-rejoint': 'evenement-rejoint', 'evenement-revolte': 'evenement-revolte', 'evenement-conquise': 'evenement-conquise', 'evenement-decouverte': 'evenement-decouverte', 'evenement-rien': 'evenement-rien', 'evenement-perdu': 'evenement-perdu', 'evenement-analyse': 'evenement-analyse'}
FONDS.update({'page-' + page: 'page/' + page for page in ('tableauBord', 'listePlanete', 'listeVaisseau', 'listePerso', 'classement', 'listeCamp', 'inscription', 'connection')})
EXTENSIONS = ('.png', '.jpg', '.jpeg', '.webp')
COTE_OBJET = 512
COTE_LOGO = 128
COTE_ICONE = 128
COTES_PETITS = {'logo': COTE_LOGO, 'icone': COTE_ICONE}
POIDS_ROUGE = 0.299
POIDS_VERT = 0.587
POIDS_BLEU = 0.114
MAXIMUM_COULEUR = 255.0
DIVISEUR_MARGE = 20
LARGEUR_FOND = 1600
RATIO_FOND = 16 / 9
QUALITE_JPEG = 82
TAILLE_COIN = 8
DISTANCE_TRANSPARENTE = 30.0
DISTANCE_OPAQUE = 140.0
FOND_ROUGE_MIN = 200
FOND_BLEU_MIN = 120
FOND_VERT_MAX = 110
SEUIL_OPACITE_CADRAGE = 0.1


def couleur_du_fond(pixels):
    coins = numpy.concatenate([
        pixels[:TAILLE_COIN, :TAILLE_COIN].reshape(-1, 3),
        pixels[:TAILLE_COIN, -TAILLE_COIN:].reshape(-1, 3),
        pixels[-TAILLE_COIN:, :TAILLE_COIN].reshape(-1, 3),
        pixels[-TAILLE_COIN:, -TAILLE_COIN:].reshape(-1, 3),
    ])
    fond = numpy.median(coins, axis=0)
    if fond[0] < FOND_ROUGE_MIN or fond[2] < FOND_BLEU_MIN or fond[1] > FOND_VERT_MAX:
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


def en_duotone(image):
    pixels = numpy.asarray(image).astype(numpy.float64)
    luminance = (pixels[:, :, 0] * POIDS_ROUGE + pixels[:, :, 1] * POIDS_VERT + pixels[:, :, 2] * POIDS_BLEU) / MAXIMUM_COULEUR
    pixels[:, :, 3] = pixels[:, :, 3] * numpy.clip(luminance, 0.0, 1.0)
    pixels[:, :, :3] = MAXIMUM_COULEUR
    return Image.fromarray(pixels.astype(numpy.uint8), 'RGBA')


def cadrer(image, cote):
    opacite = numpy.asarray(image)[:, :, 3] / 255.0
    lignes = numpy.where(opacite.max(axis=1) > SEUIL_OPACITE_CADRAGE)[0]
    colonnes = numpy.where(opacite.max(axis=0) > SEUIL_OPACITE_CADRAGE)[0]
    image = image.crop((colonnes[0], lignes[0], colonnes[-1] + 1, lignes[-1] + 1))
    utile = cote - 2 * (cote // DIVISEUR_MARGE)
    echelle = utile / max(image.size)
    image = image.resize((max(1, round(image.width * echelle)), max(1, round(image.height * echelle))), Image.LANCZOS)
    carre = Image.new('RGBA', (cote, cote), (0, 0, 0, 0))
    carre.paste(image, ((cote - image.width) // 2, (cote - image.height) // 2))
    return carre


def charger_raccord():
    spec = importlib.util.spec_from_file_location('raccorder_planete', os.path.join(os.path.dirname(os.path.abspath(__file__)), 'raccorder-planete.py'))
    module = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(module)
    return module


def traiter(chemin, nom, destination):
    base = nom.rsplit('.', 1)[0]
    dossier, _, cle = base.partition('-')
    if dossier == 'planete' and cle.isdigit():
        raccord = charger_raccord()
        image = raccord.raccorder(Image.open(chemin))
        rapport = raccord.rapport_couture(image)
        if rapport > raccord.SEUIL_COUTURE:
            print('  ignoré %s : la couture reste visible (rapport %.2f)' % (nom, rapport))
            return False
        sortie = os.path.join(destination, 'planete', cle + '.jpg')
        os.makedirs(os.path.dirname(sortie), exist_ok=True)
        image.save(sortie, quality=raccord.QUALITE_JPEG)
        print('  %s -> %s (couture %.2f)' % (nom, sortie, rapport))
        return True
    if dossier in OBJETS and cle != '':
        image = detourer(Image.open(chemin))
        if image is None:
            print('  ignoré %s : le fond n\'est pas un magenta uni dans les 4 coins' % nom)
            return False
        sortie = os.path.join(destination, dossier, *cle.split('-', 1)) + '.png'
        os.makedirs(os.path.dirname(sortie), exist_ok=True)
        if dossier == 'icone':
            image = en_duotone(image)
        cadrer(image, COTES_PETITS.get(dossier, COTE_OBJET)).save(sortie, optimize=True)
        print('  %s -> %s' % (nom, sortie))
        return True
    if base in FONDS:
        image = Image.open(chemin).convert('RGB')
        hauteur_voulue = round(image.width / RATIO_FOND)
        if image.height > hauteur_voulue:
            haut = (image.height - hauteur_voulue) // 2
            image = image.crop((0, haut, image.width, haut + hauteur_voulue))
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
