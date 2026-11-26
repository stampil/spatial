(function () {
    'use strict';

    const CLE = document.currentScript.dataset.cle;
    const ADRESSE_RECONNEXION = document.currentScript.dataset.reconnexion;
    const ADRESSE_ACCUEIL = document.currentScript.dataset.accueil;
    let jeton = null;

    try {
        jeton = localStorage.getItem(CLE);
    } catch (erreur) {
        return;
    }
    if (jeton === null) {
        return;
    }
    const corps = new URLSearchParams();
    corps.append('jeton', jeton);
    fetch(ADRESSE_RECONNEXION, {method: 'POST', body: corps, credentials: 'same-origin'}).then((reponse) => {
        if (reponse.ok) {
            location.replace(ADRESSE_ACCUEIL);
            return;
        }
        localStorage.removeItem(CLE);
    });
})();
