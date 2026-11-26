(function () {
    'use strict';

    const ADRESSE = document.currentScript.dataset.adresse;
    const INTERVALLE = 30000;
    const INTERVALLE_INJOIGNABLE = 5000;
    const SEUIL_LENT = 3000;
    const DELAI_MAX = 10000;
    const DUREE_RETABLI = 4000;
    const ETAT_OK = 'ok';
    const ETAT_LENT = 'lent';
    const ETAT_INJOIGNABLE = 'injoignable';
    const LIBELLES = {};
    LIBELLES[ETAT_OK] = 'Serveur OK';
    LIBELLES[ETAT_LENT] = 'Serveur lent';
    LIBELLES[ETAT_INJOIGNABLE] = 'Serveur injoignable';
    const TEXTE_INJOIGNABLE = 'Le serveur ne répond plus, nouvelle tentative…';
    const TEXTE_RETABLI = 'Connexion rétablie';

    const pastille = document.getElementById('sante');
    const bandeau = document.getElementById('sante-bandeau');
    let etat = null;
    let minuteur = null;
    let minuteurBandeau = null;
    let enCours = false;

    function afficher(nouvelEtat) {
        const precedent = etat;
        etat = nouvelEtat;
        pastille.hidden = false;
        pastille.dataset.etat = nouvelEtat;
        pastille.querySelector('.sante__texte').textContent = LIBELLES[nouvelEtat];
        clearTimeout(minuteurBandeau);
        if (nouvelEtat === ETAT_INJOIGNABLE) {
            bandeau.textContent = TEXTE_INJOIGNABLE;
            bandeau.hidden = false;
        } else if (precedent === ETAT_INJOIGNABLE) {
            bandeau.textContent = TEXTE_RETABLI;
            bandeau.hidden = false;
            minuteurBandeau = setTimeout(() => {
                bandeau.hidden = true;
            }, DUREE_RETABLI);
        }
    }

    function programmer() {
        clearTimeout(minuteur);
        minuteur = setTimeout(mesurer, etat === ETAT_INJOIGNABLE ? INTERVALLE_INJOIGNABLE : INTERVALLE);
    }

    function mesurer() {
        if (enCours) {
            return;
        }
        if (document.hidden) {
            programmer();
            return;
        }
        enCours = true;
        const debut = performance.now();
        const controle = new AbortController();
        const delai = setTimeout(() => controle.abort(), DELAI_MAX);
        fetch(ADRESSE, {cache: 'no-store', credentials: 'omit', signal: controle.signal}).then((reponse) => {
            if (!reponse.ok) {
                throw new Error('statut');
            }
            return reponse.json();
        }).then((donnees) => {
            if (donnees.ok !== true) {
                throw new Error('contenu');
            }
            afficher(performance.now() - debut > SEUIL_LENT ? ETAT_LENT : ETAT_OK);
        }).catch(() => {
            afficher(ETAT_INJOIGNABLE);
        }).finally(() => {
            clearTimeout(delai);
            enCours = false;
            programmer();
        });
    }

    document.addEventListener('sante-verifier', () => {
        clearTimeout(minuteur);
        mesurer();
    });
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            clearTimeout(minuteur);
            mesurer();
        }
    });
    mesurer();
})();
