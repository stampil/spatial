(function () {
    'use strict';

    const ADRESSE = document.currentScript.dataset.adresse;
    const INTERVALLE = 30000;
    const INTERVALLE_INJOIGNABLE = 5000;
    const SEUIL_LENT = 3000;
    const DELAI_MAX = 10000;
    const MILLISECONDES = 1000;
    const DUREE_RETABLI = 4000;
    const ETAT_OK = 'ok';
    const ETAT_LENT = 'lent';
    const ETAT_INJOIGNABLE = 'injoignable';
    const ETAT_INDETERMINE = 'indetermine';
    const STATUT_ERREUR_SERVEUR = 500;
    const MARQUE_OK = /"ok"\s*:\s*true/;
    const LIBELLES = {};
    LIBELLES[ETAT_OK] = 'Serveur OK';
    LIBELLES[ETAT_LENT] = 'Serveur lent';
    LIBELLES[ETAT_INJOIGNABLE] = 'Serveur injoignable';
    LIBELLES[ETAT_INDETERMINE] = 'Santé indéterminée';
    const CAUSE_DELAI = 'délai de ' + (DELAI_MAX / MILLISECONDES) + ' s dépassé';
    const CAUSE_RESEAU = 'erreur réseau';
    const CAUSE_INATTENDUE = 'réponse inattendue (page intermédiaire de l’hébergeur ?)';
    const TEXTE_RETABLI = 'Connexion rétablie';

    const pastille = document.getElementById('sante');
    const bandeau = document.getElementById('sante-bandeau');
    let etat = null;
    let minuteur = null;
    let minuteurBandeau = null;
    let enCours = false;

    function afficher(nouvelEtat, cause) {
        const precedent = etat;
        etat = nouvelEtat;
        pastille.hidden = false;
        pastille.dataset.etat = nouvelEtat;
        pastille.title = cause || '';
        pastille.querySelector('.sante__texte').textContent = LIBELLES[nouvelEtat];
        clearTimeout(minuteurBandeau);
        if (nouvelEtat === ETAT_INJOIGNABLE) {
            bandeau.textContent = 'Le serveur ne répond plus (' + cause + '), nouvelle tentative…';
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
        fetch(ADRESSE, {cache: 'no-store', credentials: 'same-origin', signal: controle.signal}).then((reponse) => {
            return reponse.text().then((texte) => {
                if (reponse.status >= STATUT_ERREUR_SERVEUR) {
                    afficher(ETAT_INJOIGNABLE, 'erreur serveur HTTP ' + reponse.status);
                } else if (reponse.ok && MARQUE_OK.test(texte)) {
                    afficher(performance.now() - debut > SEUIL_LENT ? ETAT_LENT : ETAT_OK);
                } else {
                    afficher(ETAT_INDETERMINE, CAUSE_INATTENDUE + ' : HTTP ' + reponse.status);
                }
            });
        }).catch((erreur) => {
            afficher(ETAT_INJOIGNABLE, erreur && erreur.name === 'AbortError' ? CAUSE_DELAI : CAUSE_RESEAU);
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
