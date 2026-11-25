(function () {
    'use strict';

    const CLE_MUET = 'musique_muet';
    const CLE_POSITION = 'musique_position';
    const MUET = '1';
    const ACTIF = '0';
    const VOLUME = 0.4;
    const HAVE_METADATA = 1;
    const bouton = document.querySelector('.musique');
    const piste = document.querySelector('.musique__piste');
    let voulue = lire(CLE_MUET) !== MUET;

    function lire(cle) {
        try {
            return localStorage.getItem(cle);
        } catch (erreur) {
            return null;
        }
    }

    function ecrire(cle, valeur) {
        try {
            localStorage.setItem(cle, valeur);
        } catch (erreur) {
            return;
        }
    }

    function afficher() {
        bouton.setAttribute('aria-pressed', voulue ? 'true' : 'false');
    }

    function attendreInteraction() {
        function reprendre(evenement) {
            if (bouton.contains(evenement.target)) {
                return;
            }
            document.removeEventListener('pointerdown', reprendre);
            document.removeEventListener('keydown', reprendre);
            demarrer();
        }
        document.addEventListener('pointerdown', reprendre);
        document.addEventListener('keydown', reprendre);
    }

    function demarrer() {
        if (!voulue) {
            return;
        }
        piste.play().catch(attendreInteraction);
    }

    piste.volume = VOLUME;
    const position = parseFloat(lire(CLE_POSITION));
    if (Number.isFinite(position)) {
        if (piste.readyState >= HAVE_METADATA) {
            piste.currentTime = position;
        } else {
            piste.addEventListener('loadedmetadata', function () {
                piste.currentTime = position;
            }, {once: true});
        }
    }

    bouton.addEventListener('click', function () {
        if (voulue && piste.paused) {
            demarrer();
            return;
        }
        voulue = !voulue;
        ecrire(CLE_MUET, voulue ? ACTIF : MUET);
        afficher();
        if (voulue) {
            demarrer();
        } else {
            piste.pause();
        }
    });

    window.addEventListener('pagehide', function () {
        ecrire(CLE_POSITION, String(piste.currentTime));
    });

    afficher();
    demarrer();
})();
