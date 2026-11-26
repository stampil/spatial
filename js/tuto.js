(function () {
    'use strict';

    const MARGE_ECRAN = 96;
    const HAUT_ENTETE = 120;
    const DEFILEMENT_FLUIDE = 'smooth';
    let fleche = null;
    let cible = null;
    let defile = false;

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

    function chercherCible() {
        const page = document.querySelector('main.md3-page');
        if (!page) {
            return null;
        }
        if (page.dataset.tutoMenu) {
            const adresse = '[href="?p=' + page.dataset.tutoMenu + '"]';
            return premierVisible(['.md3-navigation-bar__item' + adresse, '.md3-top-app-bar__nav a' + adresse]);
        }
        if (page.dataset.tutoCible) {
            return premierVisible(page.dataset.tutoCible.split(' ').map((cle) => '[data-tuto~="' + cle + '"]'));
        }
        return null;
    }

    function placer() {
        if (!fleche || !cible || !document.body.contains(cible)) {
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
        cible = chercherCible();
        if (!cible) {
            return;
        }
        fleche = document.createElement('div');
        fleche.className = 'tuto-fleche';
        fleche.setAttribute('aria-hidden', 'true');
        document.body.appendChild(fleche);
        const rectangle = cible.getBoundingClientRect();
        if (!defile && (rectangle.top > window.innerHeight - MARGE_ECRAN || rectangle.bottom < HAUT_ENTETE)) {
            defile = true;
            cible.scrollIntoView({behavior: DEFILEMENT_FLUIDE, block: 'center'});
        }
        placer();
    }

    document.addEventListener('DOMContentLoaded', afficher);
    document.addEventListener('page-rafraichie', afficher);
    window.addEventListener('load', placer);
    document.fonts.ready.then(placer);
    window.addEventListener('scroll', placer, {passive: true});
    window.addEventListener('resize', placer);
})();
