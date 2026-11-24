(function () {
    'use strict';

    const DELAI_ETAPE = 1400;
    const POURCENT = 100;
    const TOUCHE_ECHAP = 'Escape';
    const dialogues = Array.from(document.querySelectorAll('.md3-dialog'));
    const mouvementReduit = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function creer(nom, classe, texte) {
        const noeud = document.createElement(nom);
        noeud.className = classe || '';
        if (texte !== undefined) {
            noeud.textContent = texte;
        }
        return noeud;
    }

    function jouer(indice) {
        if (indice >= dialogues.length) {
            document.body.classList.remove('md3-dialog-ouvert');
            return;
        }
        const dialogue = dialogues[indice];
        const rapport = JSON.parse(dialogue.querySelector('.rapport__donnees').textContent);
        const conteneurBarres = dialogue.querySelector('.rapport__barres');
        const journal = dialogue.querySelector('.rapport__journal');
        const bilan = dialogue.querySelector('.rapport__bilan');
        const passer = dialogue.querySelector('.rapport__passer');
        const fermer = dialogue.querySelector('.rapport__fermer');
        const barres = {};
        const minuteurs = [];
        let termine = false;

        rapport.barres.forEach((barre) => {
            const remplissage = creer('div', 'rapport__remplissage rapport__remplissage--' + barre.cle);
            const valeur = creer('span', 'rapport__valeur');
            const ligne = creer('div', 'rapport__barre');
            const piste = creer('div', 'rapport__piste');
            piste.appendChild(remplissage);
            ligne.appendChild(creer('span', 'rapport__nom', barre.nom));
            ligne.appendChild(piste);
            ligne.appendChild(valeur);
            conteneurBarres.appendChild(ligne);
            barres[barre.cle] = {maximum: barre.maximum, remplissage: remplissage, valeur: valeur};
            appliquer(barre.cle, barre.maximum);
        });

        function appliquer(cle, valeur) {
            const barre = barres[cle];
            const part = barre.maximum === 0 ? 0 : (valeur / barre.maximum) * POURCENT;
            barre.remplissage.style.width = part + '%';
            barre.valeur.textContent = valeur + ' / ' + barre.maximum;
        }

        function montrerEtape(etape) {
            journal.appendChild(creer('li', '', etape.texte));
            Object.keys(etape.valeurs).forEach((cle) => {
                appliquer(cle, etape.valeurs[cle]);
            });
        }

        function conclure() {
            if (termine) {
                return;
            }
            termine = true;
            minuteurs.forEach(clearTimeout);
            while (journal.children.length < rapport.etapes.length) {
                montrerEtape(rapport.etapes[journal.children.length]);
            }
            bilan.textContent = rapport.bilan;
            bilan.hidden = false;
            passer.hidden = true;
            fermer.hidden = false;
            fermer.focus();
        }

        function fermerDialogue() {
            dialogue.hidden = true;
            document.removeEventListener('keydown', surTouche);
            jouer(indice + 1);
        }

        function surTouche(evenement) {
            if (evenement.key === TOUCHE_ECHAP) {
                if (termine) {
                    fermerDialogue();
                } else {
                    conclure();
                }
            }
        }

        passer.addEventListener('click', conclure);
        fermer.addEventListener('click', fermerDialogue);
        document.addEventListener('keydown', surTouche);
        dialogue.hidden = false;
        document.body.classList.add('md3-dialog-ouvert');
        passer.focus();

        if (mouvementReduit) {
            conclure();
            return;
        }
        rapport.etapes.forEach((etape, position) => {
            minuteurs.push(setTimeout(() => montrerEtape(etape), DELAI_ETAPE * (position + 1)));
        });
        minuteurs.push(setTimeout(conclure, DELAI_ETAPE * (rapport.etapes.length + 1)));
    }

    if (dialogues.length > 0) {
        jouer(0);
    }
})();
