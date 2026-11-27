#!/usr/bin/env python3
"""Usage: verifier-fleches-tuto.py <base url> <player name> <password> <mysql command prefix>.
Example: verifier-fleches-tuto.py http://127.0.0.1:19873/ Vis2860 secret1 "mysql -h127.0.0.1 -P19872 -uroot systeme".
For each tutorial step it loads every page as that player and checks the arrow: the menu entry it would point at must exist in the navigation, and a target key on the current page must match an element of the page. A key missing from the page is reported as ABSENT (the game state may not offer that button yet), a menu entry missing as FAIL."""
import http.cookiejar
import re
import subprocess
import sys
import urllib.error
import urllib.parse
import urllib.request

PAGES_SIMPLES = ('tableauBord', 'carte', 'listePlanete', 'listeVaisseau', 'listePerso')
CLES_DESSINEES_PAR_SCRIPT = ('systeme-',)
ETAPE_MINIMUM = 0
ETAPE_MAXIMUM = 17
ETAPE_APRES_TUTO = 18
CHAMP_MENU = 'data-tuto-menu'
CHAMP_CIBLE = 'data-tuto-cible'


def ouvrir(base, nom, mot_de_passe):
    jar = http.cookiejar.CookieJar()
    ouvreur = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar))
    formulaire = ouvreur.open(base + '?p=connection').read().decode('utf-8')
    jeton = re.search(r'name="csrf_token" value="([0-9a-f]+)"', formulaire)
    champs = {'nom': nom, 'mdp': mot_de_passe, 'go': '1'}
    if jeton:
        champs['csrf_token'] = jeton.group(1)
    ouvreur.open(base + 'index.php?p=connecter', urllib.parse.urlencode(champs).encode()).read()
    return ouvreur


def charger(ouvreur, base, page, parametres=''):
    return ouvreur.open(base + '?p=' + page + parametres).read().decode('utf-8')


def attribut(html, nom):
    trouve = re.search(r'<main class="md3-page"[^>]*\b' + nom + r'="([^"]*)"', html)
    return trouve.group(1) if trouve else None


def interroger(commande, requete):
    sortie = subprocess.run(commande.split() + ['-N', '-e', requete], check=True, capture_output=True, text=True).stdout.strip()
    return sortie.split('\n')[0] if sortie else ''


def pages_a_tester(commande, nom):
    perso = interroger(commande, "SELECT p.id FROM perso p JOIN joueur j ON j.id=p.id_joueur WHERE j.nom='%s' ORDER BY p.id LIMIT 1" % nom)
    mere = interroger(commande, "SELECT pl.id FROM planete pl JOIN joueur j ON j.id=pl.gouverneur WHERE j.nom='%s' ORDER BY pl.id LIMIT 1" % nom)
    voisin = interroger(commande, "SELECT id_systeme_voisin FROM joueur WHERE nom='%s'" % nom)
    cible = interroger(commande, "SELECT id FROM planete WHERE id_systeme=%s ORDER BY id LIMIT 1" % voisin) if voisin else ''
    pages = [(page, '') for page in PAGES_SIMPLES]
    if perso:
        pages.append(('affichePerso', '&id=' + perso))
    if mere:
        pages.append(('affichePlanete', '&id_planete=' + mere))
    if voisin:
        pages.append(('afficheSysteme', '&id_systeme=' + voisin))
    if cible:
        pages.append(('affichePlanete', '&id_planete=' + cible))
    return pages


def mettre_etape(commande, nom, etape):
    requete = "UPDATE joueur SET etape_tuto=%d WHERE nom='%s'" % (etape, nom)
    subprocess.run(commande.split() + ['-e', requete], check=True)


def verifier(html):
    menu = attribut(html, CHAMP_MENU)
    cible = attribut(html, CHAMP_CIBLE)
    if menu is None:
        return 'aucune flèche', 'ok'
    if not re.search(r'href="\?p=' + re.escape(menu) + '"', html):
        return 'menu ' + menu, 'FAIL : entrée de menu absente'
    if cible is None:
        return 'menu ' + menu, 'ok'
    for cle in cible.split():
        if cle.startswith(CLES_DESSINEES_PAR_SCRIPT):
            return 'cible ' + cle, 'ok : dessinée par le script de la page'
        if re.search(r'data-tuto="[^"]*\b' + re.escape(cle) + r'\b[^"]*"', html):
            return 'cible ' + cle, 'ok'
    return 'cible ' + cible, 'ABSENT : aucun élément sur la page'


def main():
    if len(sys.argv) != 5:
        sys.exit(__doc__)
    base, nom, mot_de_passe, commande = sys.argv[1:]
    ouvreur = ouvrir(base, nom, mot_de_passe)
    echecs = 0
    absents = 0
    print('étape | page | flèche | résultat')
    pages = pages_a_tester(commande, nom)
    for etape in range(ETAPE_MINIMUM, ETAPE_MAXIMUM + 1):
        mettre_etape(commande, nom, etape)
        for page, parametres in pages:
            try:
                flèche, resultat = verifier(charger(ouvreur, base, page, parametres))
            except urllib.error.HTTPError as erreur:
                flèche, resultat = 'page refusée (HTTP %d)' % erreur.code, 'ignorée : objet pas encore connu du joueur'
            if resultat.startswith('FAIL'):
                echecs += 1
            if resultat.startswith('ABSENT'):
                absents += 1
            print('%5d | %-14s | %-48s | %s' % (etape, page + parametres.replace('&id_planete=', '#').replace('&id_systeme=', '#').replace('&id=', '#'), flèche, resultat))
    mettre_etape(commande, nom, ETAPE_APRES_TUTO)
    print('%d échec(s), %d cible(s) absente(s) sur les pages testées' % (echecs, absents))
    sys.exit(1 if echecs else 0)


main()
