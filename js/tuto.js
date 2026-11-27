(function () {
    'use strict';

    const MARGE_ECRAN = 96;
    const HAUT_ENTETE = 120;
    const DEFILEMENT_FLUIDE = 'smooth';
    const ATTRIBUTS_SANS_RECHARGEMENT = '[data-bascule], [data-flotte-creer], [data-flotte-annuler]';
    let fleche = null;
    let cible = null;
    let derniereCible = null;
    let attenteRafraichissement = false;
    let miseAJourPrevue = false;
    let observateur = null;

    function visible(noeud) {
        const rectangle = noeud.getBoundingClientRect();
        return rectangle.width > 0 && rectangle.height > 0;
    }

    function premierVisible(selecteurs) {
        for (const selecteur of selecteurs) {
            const trouve = [].slice.call(document.querySelectorAll(selecteur)).find(visible);
            if (trouve) {
                return trouve;
            }
        }
        return null;
    }

    function lienDeMenu(page) {
        const adresse = '[href="?p=' + page + '"]';
        const lien = premierVisible(['.md3-navigation-bar__item' + adresse, '.md3-top-app-bar__nav a' + adresse]);
        if (lien && lien.getAttribute('aria-current') === 'page') {
            return null;
        }
        return lien;
    }

    function chercherCible() {
        const page = document.querySelector('main.md3-page');
        if (!page) {
            return null;
        }
        let trouve = null;
        if (page.dataset.tutoCible) {
            trouve = premierVisible(page.dataset.tutoCible.split(' ').map((cle) => '[data-tuto~="' + cle + '"]'));
        }
        if (!trouve && page.dataset.tutoMenu) {
            trouve = lienDeMenu(page.dataset.tutoMenu);
        }
        return trouve;
    }

    function placer() {
        if (!fleche || !cible) {
            return;
        }
        const rectangle = cible.getBoundingClientRect();
        const hauteurFleche = fleche.offsetHeight;
        const sousLaCible = rectangle.top < HAUT_ENTETE;
        fleche.classList.toggle('tuto-fleche--vers-le-haut', sousLaCible);
        fleche.style.left = (rectangle.left + rectangle.width / 2 - fleche.offsetWidth / 2) + 'px';
        fleche.style.top = (sousLaCible ? rectangle.bottom : rectangle.top - hauteurFleche) + 'px';
    }

    function retirer() {
        if (fleche) {
            fleche.remove();
        }
        fleche = null;
        cible = null;
    }

    function afficher() {
        retirer();
        if (attenteRafraichissement) {
            return;
        }
        cible = chercherCible();
        if (!cible) {
            return;
        }
        fleche = document.createElement('div');
        fleche.className = 'tuto-fleche';
        fleche.setAttribute('aria-hidden', 'true');
        document.body.appendChild(fleche);
        const rectangle = cible.getBoundingClientRect();
        if (cible !== derniereCible && (rectangle.top > window.innerHeight - MARGE_ECRAN || rectangle.bottom < HAUT_ENTETE)) {
            cible.scrollIntoView({behavior: DEFILEMENT_FLUIDE, block: 'center'});
        }
        derniereCible = cible;
        placer();
    }

    function reevaluer() {
        if (miseAJourPrevue) {
            return;
        }
        miseAJourPrevue = true;
        window.requestAnimationFrame(() => {
            miseAJourPrevue = false;
            afficher();
        });
    }

    function suivre() {
        if (fleche && cible) {
            if (document.body.contains(cible) && visible(cible)) {
                placer();
            } else {
                reevaluer();
            }
        }
        window.requestAnimationFrame(suivre);
    }

    function surveiller() {
        const page = document.querySelector('main.md3-page');
        if (observateur) {
            observateur.disconnect();
        }
        if (page) {
            observateur = new MutationObserver(reevaluer);
            observateur.observe(page, {attributes: true, attributeFilter: ['hidden', 'class', 'disabled'], subtree: true});
        }
    }

    function relancer() {
        attenteRafraichissement = false;
        surveiller();
        afficher();
    }

    document.addEventListener('click', (evenement) => {
        if (!cible || !cible.contains(evenement.target)) {
            return;
        }
        if (cible.matches(ATTRIBUTS_SANS_RECHARGEMENT)) {
            reevaluer();
            return;
        }
        attenteRafraichissement = true;
        retirer();
    });

    document.addEventListener('DOMContentLoaded', () => {
        relancer();
        window.requestAnimationFrame(suivre);
    });
    document.addEventListener('page-rafraichie', relancer);
    document.addEventListener('sante-verifier', relancer);
    window.addEventListener('load', placer);
    window.addEventListener('resize', placer);
})();
