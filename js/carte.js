(function () {
    'use strict';

    const racine = document.getElementById('carte');
    if (!racine) {
        return;
    }

    const DONNEES = JSON.parse(document.getElementById('carte-donnees').textContent);
    const JETON = racine.dataset.jeton;
    const SVG_NS = 'http://www.w3.org/2000/svg';
    const ECHELLE_MIN = 0.1;
    const ECHELLE_MAX = 4;
    const ECHELLE_AJUSTEMENT_MAX = 2;
    const ECHELLE_BASE = 1;
    const ECHELLE_LISIBLE = 0.6;
    const ECHELLE_DEPART = 0.8;
    const ECHELLE_NOMS = 0.7;
    const MARGE_AJUSTEMENT = 70;
    const ETENDUE_MIN = 300;
    const RAYON_TOUCHER = 24;
    const RAYON_NOEUD = 7;
    const RAYON_PAR_PLANETE = 0.5;
    const SEUIL_GLISSER = 8;
    const PAS_ZOOM = 1.3;
    const NB_ETOILES = 160;
    const MARGE_ETOILES = 300;
    const GRAINE_ETOILES = 20221219;
    const MULTIPLICATEUR_ALEATOIRE = 1103515245;
    const INCREMENT_ALEATOIRE = 12345;
    const MODULE_ALEATOIRE = 2147483648;
    const DELAI_RECHARGE_MIN = 5000;
    const MILLISECONDES = 1000;
    const SECONDES_MINUTE = 60;
    const SECONDES_HEURE = 3600;
    const ADRESSE_FLOTTES = '?p=listeVaisseau';
    const MESSAGE_ECHEC_ENVOI_AVANT = 'Ordre non confirmé (';
    const MESSAGE_ECHEC_ENVOI_APRES = ') : vérifiez la page Flottes avant de recommencer.';
    const ADRESSE_SYSTEME = '?p=afficheSysteme&id_systeme=';
    const TOUCHE_ENTREE = 'Enter';
    const TOUCHE_ESPACE = ' ';
    const TOUCHE_ECHAP = 'Escape';
    const DEGRES_DEMI_TOUR = 180;
    const DECALAGE_ANGLE_FLECHE = 90;
    const TAILLE_POLICE_NOM = 13;
    const CONTOUR_NOM = 3;
    const DECALAGE_NOM = 17;
    const ECART_HALO = 6;
    const RAYON_ETOILE_MIN = 0.6;
    const RAYON_ETOILE_VARIATION = 1.2;

    const decalage = DONNEES.maintenant * MILLISECONDES - Date.now();
    const svgRacine = racine.querySelector('.carte__svg');
    const feuille = document.getElementById('carte-feuille');
    const feuilleTitre = document.getElementById('carte-feuille-titre');
    const feuilleSousTitre = document.getElementById('carte-feuille-sous-titre');
    const feuilleCorps = document.getElementById('carte-feuille-corps');
    const bandeau = document.getElementById('carte-bandeau');
    const bandeauTexte = document.getElementById('carte-bandeau-texte');
    const bandeauSuite = document.getElementById('carte-bandeau-suite');
    const boutonTrajets = document.getElementById('carte-trajets');

    const systemes = {};
    const positions = {};
    const noeuds = {};
    const adaptables = [];
    const compteurs = [];
    const marqueurs = [];
    let vue = {x: 0, y: 0, echelle: 1};
    let ordre = null;
    let envoiEnCours = false;

    DONNEES.systemes.forEach((systeme) => {
        systemes[systeme.id] = systeme;
        positions[systeme.id] = {x: systeme.x, y: systeme.y};
    });

    function maintenant() {
        return (Date.now() + decalage) / MILLISECONDES;
    }

    function formaterDuree(secondes) {
        const total = Math.max(0, Math.round(secondes));
        const heures = Math.floor(total / SECONDES_HEURE);
        const minutes = Math.floor((total % SECONDES_HEURE) / SECONDES_MINUTE);
        const reste = total % SECONDES_MINUTE;
        const deuxChiffres = (valeur) => String(valeur).padStart(2, '0');
        if (heures > 0) {
            return heures + ':' + deuxChiffres(minutes) + ':' + deuxChiffres(reste);
        }
        return deuxChiffres(minutes) + ':' + deuxChiffres(reste);
    }

    function creer(nom, attributs, enfants) {
        const noeud = document.createElement(nom);
        Object.keys(attributs || {}).forEach((cle) => {
            if (cle === 'texte') {
                noeud.textContent = attributs[cle];
            } else if (cle === 'classe') {
                noeud.className = attributs[cle];
            } else {
                noeud.setAttribute(cle, attributs[cle]);
            }
        });
        (enfants || []).forEach((enfant) => {
            noeud.appendChild(typeof enfant === 'string' ? document.createTextNode(enfant) : enfant);
        });
        return noeud;
    }

    function creerSvg(nom, attributs) {
        const noeud = document.createElementNS(SVG_NS, nom);
        Object.keys(attributs || {}).forEach((cle) => {
            noeud.setAttribute(cle, attributs[cle]);
        });
        return noeud;
    }

    function boutonVoyager(action) {
        const noeud = document.getElementById('carte-modele-voyager').content.firstElementChild.cloneNode(true);
        noeud.addEventListener('click', () => action(noeud));
        return noeud;
    }

    function envoyer(adresse, champs, declencheur) {
        if (envoiEnCours) {
            return;
        }
        envoiEnCours = true;
        window.Occupation.demarrer(declencheur);
        const corps = new URLSearchParams();
        corps.append('csrf_token', JETON);
        Object.keys(champs).forEach((cle) => {
            [].concat(champs[cle]).forEach((valeur) => {
                corps.append(cle, valeur);
            });
        });
        window.Requete.demander(adresse, {method: 'POST', body: corps, credentials: 'same-origin'}).then(() => {
            location.replace(ADRESSE_FLOTTES);
        }).catch((erreur) => {
            envoiEnCours = false;
            window.Occupation.liberer(declencheur);
            document.dispatchEvent(new Event('sante-verifier'));
            bandeauTexte.textContent = MESSAGE_ECHEC_ENVOI_AVANT + erreur.message + MESSAGE_ECHEC_ENVOI_APRES;
            bandeau.classList.add('carte__bandeau--erreur');
            bandeauSuite.hidden = true;
        });
    }

    function recharger() {
        const cle = 'carteRecharge';
        let dernier = 0;
        try {
            dernier = Number(sessionStorage.getItem(cle)) || 0;
        } catch (erreur) {
            dernier = 0;
        }
        if (Date.now() - dernier < DELAI_RECHARGE_MIN) {
            return;
        }
        try {
            sessionStorage.setItem(cle, String(Date.now()));
        } catch (erreur) {
            return;
        }
        location.reload();
    }

    function adapter(fonction) {
        adaptables.push(fonction);
        fonction(vue.echelle);
    }

    function appliquerVue() {
        monde.setAttribute('transform', 'translate(' + vue.x + ' ' + vue.y + ') scale(' + vue.echelle + ')');
        adaptables.forEach((fonction) => {
            fonction(vue.echelle);
        });
    }

    function taille() {
        const rectangle = svgRacine.getBoundingClientRect();
        return {largeur: rectangle.width, hauteur: rectangle.height};
    }

    function ajuster(identifiants, echelleMax) {
        const dimensions = taille();
        let xMin = Infinity;
        let xMax = -Infinity;
        let yMin = Infinity;
        let yMax = -Infinity;
        identifiants.forEach((identifiant) => {
            const position = positions[identifiant];
            xMin = Math.min(xMin, position.x);
            xMax = Math.max(xMax, position.x);
            yMin = Math.min(yMin, position.y);
            yMax = Math.max(yMax, position.y);
        });
        const largeur = Math.max(xMax - xMin, ETENDUE_MIN);
        const hauteur = Math.max(yMax - yMin, ETENDUE_MIN);
        const echelle = Math.min(
            (dimensions.largeur - 2 * MARGE_AJUSTEMENT) / largeur,
            (dimensions.hauteur - 2 * MARGE_AJUSTEMENT) / hauteur,
            echelleMax
        );
        vue.echelle = Math.max(ECHELLE_MIN, echelle);
        vue.x = dimensions.largeur / 2 - ((xMin + xMax) / 2) * vue.echelle;
        vue.y = dimensions.hauteur / 2 - ((yMin + yMax) / 2) * vue.echelle;
        appliquerVue();
    }

    function zoomer(facteur, centreX, centreY) {
        const nouvelle = Math.min(ECHELLE_MAX, Math.max(ECHELLE_MIN, vue.echelle * facteur));
        const rapport = nouvelle / vue.echelle;
        vue.x = centreX - (centreX - vue.x) * rapport;
        vue.y = centreY - (centreY - vue.y) * rapport;
        vue.echelle = nouvelle;
        appliquerVue();
    }

    function zoomerAuCentre(facteur) {
        const dimensions = taille();
        zoomer(facteur, dimensions.largeur / 2, dimensions.hauteur / 2);
    }

    function aleatoire(graine) {
        let etat = graine;
        return () => {
            etat = (etat * MULTIPLICATEUR_ALEATOIRE + INCREMENT_ALEATOIRE) % MODULE_ALEATOIRE;
            return etat / MODULE_ALEATOIRE;
        };
    }

    const monde = creerSvg('g');
    const calqueEtoiles = creerSvg('g');
    const calqueLignes = creerSvg('g');
    const calqueSystemes = creerSvg('g');
    const calqueMarqueurs = creerSvg('g');
    monde.appendChild(calqueEtoiles);
    monde.appendChild(calqueLignes);
    monde.appendChild(calqueSystemes);
    monde.appendChild(calqueMarqueurs);
    svgRacine.appendChild(monde);

    function dessinerEtoiles() {
        const identifiants = Object.keys(positions);
        let xMin = Infinity;
        let xMax = -Infinity;
        let yMin = Infinity;
        let yMax = -Infinity;
        identifiants.forEach((identifiant) => {
            xMin = Math.min(xMin, positions[identifiant].x);
            xMax = Math.max(xMax, positions[identifiant].x);
            yMin = Math.min(yMin, positions[identifiant].y);
            yMax = Math.max(yMax, positions[identifiant].y);
        });
        const hasard = aleatoire(GRAINE_ETOILES);
        for (let i = 0; i < NB_ETOILES; i++) {
            const etoile = creerSvg('circle', {
                'class': 'carte__etoile',
                cx: xMin - MARGE_ETOILES + hasard() * (xMax - xMin + 2 * MARGE_ETOILES),
                cy: yMin - MARGE_ETOILES + hasard() * (yMax - yMin + 2 * MARGE_ETOILES)
            });
            const rayon = RAYON_ETOILE_MIN + hasard() * RAYON_ETOILE_VARIATION;
            calqueEtoiles.appendChild(etoile);
            adapter((echelle) => {
                etoile.setAttribute('r', rayon / echelle);
            });
        }
    }

    function dessinerSysteme(systeme) {
        const rayon = RAYON_NOEUD + systeme.planetes.length * RAYON_PAR_PLANETE;
        const groupe = creerSvg('g', {
            'class': 'carte__systeme',
            'data-systeme': systeme.id,
            role: 'button',
            tabindex: '0',
            'aria-label': systeme.nom + ', ' + systeme.camp
        });
        const halo = creerSvg('circle', {'class': 'carte__halo', cx: systeme.x, cy: systeme.y});
        const disque = creerSvg('circle', {'class': 'carte__disque', cx: systeme.x, cy: systeme.y, fill: systeme.couleur});
        const zone = creerSvg('circle', {'class': 'carte__zone', cx: systeme.x, cy: systeme.y});
        const nom = creerSvg('text', {'class': 'carte__nom', x: systeme.x, 'text-anchor': 'middle'});
        nom.textContent = systeme.nom;
        groupe.appendChild(halo);
        groupe.appendChild(disque);
        groupe.appendChild(nom);
        groupe.appendChild(zone);
        if (systeme.moi) {
            groupe.classList.add('carte__systeme--moi');
        }
        groupe.addEventListener('keydown', (evenement) => {
            if (evenement.key === TOUCHE_ENTREE || evenement.key === TOUCHE_ESPACE) {
                evenement.preventDefault();
                toucherSysteme(systeme.id);
            }
        });
        calqueSystemes.appendChild(groupe);
        noeuds[systeme.id] = groupe;
        adapter((echelle) => {
            halo.setAttribute('r', (rayon + ECART_HALO) / echelle);
            disque.setAttribute('r', rayon / echelle);
            zone.setAttribute('r', RAYON_TOUCHER / echelle);
            nom.setAttribute('y', systeme.y + (rayon + DECALAGE_NOM) / echelle);
            nom.setAttribute('font-size', TAILLE_POLICE_NOM / echelle);
            nom.setAttribute('stroke-width', CONTOUR_NOM / echelle);
            nom.style.display = systeme.moi || echelle >= ECHELLE_NOMS ? '' : 'none';
        });
    }

    function dessinerTrajet(trajet) {
        if (trajet.expedition || trajet.vers === null || !positions[trajet.de] || !positions[trajet.vers]) {
            return;
        }
        const depart = positions[trajet.de];
        const arrivee = positions[trajet.vers];
        calqueLignes.appendChild(creerSvg('line', {
            'class': 'carte__route',
            x1: depart.x, y1: depart.y, x2: arrivee.x, y2: arrivee.y
        }));
        if (trajet.depart === null) {
            return;
        }
        const fleche = creerSvg('polygon', {'class': 'carte__vaisseau', points: '0,-7 6,6 -6,6'});
        const titre = creerSvg('title');
        titre.textContent = trajet.libelle;
        fleche.appendChild(titre);
        calqueMarqueurs.appendChild(fleche);
        marqueurs.push({trajet: trajet, fleche: fleche, depart: depart, arrivee: arrivee});
    }

    function placerMarqueurs() {
        const present = maintenant();
        marqueurs.forEach((marqueur) => {
            const trajet = marqueur.trajet;
            const avancement = Math.min(1, Math.max(0, (present - trajet.depart) / (trajet.arrivee - trajet.depart)));
            const x = marqueur.depart.x + (marqueur.arrivee.x - marqueur.depart.x) * avancement;
            const y = marqueur.depart.y + (marqueur.arrivee.y - marqueur.depart.y) * avancement;
            const angle = Math.atan2(marqueur.arrivee.y - marqueur.depart.y, marqueur.arrivee.x - marqueur.depart.x) * DEGRES_DEMI_TOUR / Math.PI + DECALAGE_ANGLE_FLECHE;
            marqueur.fleche.setAttribute('transform', 'translate(' + x + ' ' + y + ') rotate(' + angle + ') scale(' + (1 / vue.echelle) + ')');
        });
    }

    function stockDePlanete(planeteId) {
        return DONNEES.stocks.find((stock) => stock.planete === planeteId) || null;
    }

    function ouvrirFeuille(titre, sousTitre, couleur) {
        feuilleTitre.textContent = titre;
        feuilleSousTitre.textContent = sousTitre;
        feuille.style.setProperty('--carte-accent', couleur || 'transparent');
        feuilleCorps.textContent = '';
        feuille.hidden = false;
        feuilleCorps.scrollTop = 0;
    }

    function fermerFeuille() {
        feuille.hidden = true;
        marquerSelection(ordre ? ordre.systeme : null);
    }

    function marquerSelection(systemeId) {
        Object.keys(noeuds).forEach((identifiant) => {
            noeuds[identifiant].classList.toggle('carte__systeme--choisi', Number(identifiant) === systemeId);
        });
    }

    function secondesDeOrdre(systemeId) {
        let secondes = 0;
        const vaisseaux = ordre.stock ? ordre.stock.vaisseaux : [];
        vaisseaux.forEach((vaisseau) => {
            if (ordre.nombres[vaisseau.type]) {
                secondes = Math.max(secondes, vaisseau.secondes[systemeId]);
            }
        });
        ordre.flottes.forEach((flotte) => {
            secondes = Math.max(secondes, flotte.secondes[systemeId]);
        });
        return secondes;
    }

    function confirmerOrdre(planeteId, declencheur) {
        const champs = {depart: ordre.planete, destination: planeteId, 'flotte[]': ordre.flottes.map((flotte) => flotte.id)};
        if (ordre.vaisseau) {
            champs.vaisseau = ordre.vaisseau;
        } else {
            Object.keys(ordre.nombres).forEach((type) => {
                if (ordre.nombres[type] > 0) {
                    champs['nombre[' + type + ']'] = ordre.nombres[type];
                }
            });
        }
        envoyer('?p=deplacer', champs, declencheur);
    }

    function ouvrirDestination(systemeId) {
        const duree = secondesDeOrdre(systemeId);
        marquerSelection(systemeId);
        const systeme = systemes[systemeId];
        ouvrirFeuille('Voyager vers ' + systeme.nom, 'Trajet : ' + formaterDuree(duree), systeme.couleur);
        systeme.planetes.filter((planete) => planete.id !== ordre.planete).forEach((planete) => {
            feuilleCorps.appendChild(creer('div', {classe: 'md3-row carte__ligne'}, [
                creer('span', {texte: planete.nom}),
                boutonVoyager((noeud) => confirmerOrdre(planete.id, noeud))
            ]));
        });
    }

    function commencerOrdre(nouvelOrdre) {
        ordre = nouvelOrdre;
        fermerFeuille();
        bandeauTexte.textContent = ordre.libelle;
        bandeauSuite.hidden = false;
        bandeau.classList.remove('carte__bandeau--erreur');
        bandeau.hidden = false;
    }

    function annulerOrdre() {
        ordre = null;
        bandeau.hidden = true;
        fermerFeuille();
    }

    function reprendreOrdre(demande) {
        const stock = stockDePlanete(demande.planete);
        const flottes = DONNEES.flottes.filter((flotte) => flotte.planete === demande.planete && demande.flottes.includes(flotte.id));
        const nombres = {};
        let total = flottes.length;
        if (stock) {
            stock.vaisseaux.forEach((vaisseau) => {
                nombres[vaisseau.type] = Math.min(vaisseau.nombre, demande.nombres[vaisseau.type] || 0);
                total += nombres[vaisseau.type];
            });
        }
        if (total === 0) {
            return;
        }
        const systemeId = stock ? stock.systeme : flottes[0].systeme;
        centrerSur(systemeId, ECHELLE_BASE);
        commencerOrdre({planete: demande.planete, systeme: systemeId, stock: stock, nombres: nombres, flottes: flottes, vaisseau: demande.vaisseau, libelle: demande.libelle});
    }

    function toucherSysteme(systemeId) {
        if (ordre) {
            ouvrirDestination(systemeId);
        } else {
            location.href = ADRESSE_SYSTEME + systemeId;
        }
    }

    function nomDuSysteme(systemeId) {
        return systemes[systemeId] ? systemes[systemeId].nom : 'un secteur inconnu';
    }

    function ouvrirTrajets() {
        compteurs.length = 0;
        marquerSelection(null);
        ouvrirFeuille('Flottes en route', DONNEES.enRouteLibelle, null);
        DONNEES.enRoute.forEach((trajet) => {
            const duree = creer('span', {classe: 'carte__valeur'});
            compteurs.push({trajet: trajet, noeud: duree});
            feuilleCorps.appendChild(creer('div', {classe: 'md3-row carte__ligne'}, [
                creer('div', {}, [
                    creer('strong', {texte: trajet.libelle}),
                    creer('br'),
                    creer('span', {classe: 'md3-secondary', texte: trajet.expedition ? 'en expédition dans l’inconnu depuis ' + nomDuSysteme(trajet.de) : 'de ' + nomDuSysteme(trajet.de) + ' vers ' + nomDuSysteme(trajet.vers)})
                ]),
                duree
            ]));
        });
        majCompteurs();
    }

    function majCompteurs() {
        compteurs.forEach((compteur) => {
            compteur.noeud.textContent = formaterDuree(compteur.trajet.arrivee - maintenant());
        });
    }

    function tic() {
        placerMarqueurs();
        majCompteurs();
        const arrive = DONNEES.enRoute.some((trajet) => trajet.arrivee + 1 <= maintenant());
        if (arrive) {
            recharger();
        }
    }

    function nombrePointeurs(pointeurs) {
        return Object.keys(pointeurs).length;
    }

    function installerGestes() {
        const pointeurs = {};
        let geste = null;
        const distance = () => {
            const points = Object.keys(pointeurs).map((identifiant) => pointeurs[identifiant]);
            return Math.hypot(points[0].x - points[1].x, points[0].y - points[1].y);
        };
        const milieu = () => {
            const points = Object.keys(pointeurs).map((identifiant) => pointeurs[identifiant]);
            const rectangle = svgRacine.getBoundingClientRect();
            return {x: (points[0].x + points[1].x) / 2 - rectangle.left, y: (points[0].y + points[1].y) / 2 - rectangle.top};
        };

        svgRacine.addEventListener('pointerdown', (evenement) => {
            svgRacine.setPointerCapture(evenement.pointerId);
            pointeurs[evenement.pointerId] = {x: evenement.clientX, y: evenement.clientY};
            if (nombrePointeurs(pointeurs) === 1) {
                geste = {x: evenement.clientX, y: evenement.clientY, vueX: vue.x, vueY: vue.y, deplace: false};
            } else if (nombrePointeurs(pointeurs) === 2) {
                geste = {pincement: true, distance: distance(), deplace: true};
            }
        });

        svgRacine.addEventListener('pointermove', (evenement) => {
            if (!pointeurs[evenement.pointerId] || !geste) {
                return;
            }
            pointeurs[evenement.pointerId] = {x: evenement.clientX, y: evenement.clientY};
            if (geste.pincement) {
                if (nombrePointeurs(pointeurs) === 2) {
                    const nouvelle = distance();
                    const centre = milieu();
                    zoomer(nouvelle / geste.distance, centre.x, centre.y);
                    geste.distance = nouvelle;
                }
                return;
            }
            const deltaX = evenement.clientX - geste.x;
            const deltaY = evenement.clientY - geste.y;
            if (Math.hypot(deltaX, deltaY) > SEUIL_GLISSER) {
                geste.deplace = true;
            }
            if (geste.deplace) {
                vue.x = geste.vueX + deltaX;
                vue.y = geste.vueY + deltaY;
                appliquerVue();
            }
        });

        const terminer = (evenement) => {
            if (!pointeurs[evenement.pointerId]) {
                return;
            }
            const toucher = geste && !geste.deplace && nombrePointeurs(pointeurs) === 1 && evenement.type === 'pointerup';
            delete pointeurs[evenement.pointerId];
            if (nombrePointeurs(pointeurs) === 0) {
                geste = null;
            }
            if (toucher) {
                const cible = document.elementFromPoint(evenement.clientX, evenement.clientY);
                const noeud = cible ? cible.closest('[data-systeme]') : null;
                if (noeud) {
                    toucherSysteme(Number(noeud.getAttribute('data-systeme')));
                } else {
                    fermerFeuille();
                }
            }
        };
        svgRacine.addEventListener('pointerup', terminer);
        svgRacine.addEventListener('pointercancel', terminer);

        svgRacine.addEventListener('wheel', (evenement) => {
            evenement.preventDefault();
            const rectangle = svgRacine.getBoundingClientRect();
            zoomer(evenement.deltaY < 0 ? PAS_ZOOM : 1 / PAS_ZOOM, evenement.clientX - rectangle.left, evenement.clientY - rectangle.top);
        }, {passive: false});
    }

    function baseConnue() {
        return DONNEES.base !== null && positions[DONNEES.base] !== undefined;
    }

    function centrerSur(identifiant, echelle) {
        const dimensions = taille();
        vue.echelle = echelle;
        vue.x = dimensions.largeur / 2 - positions[identifiant].x * echelle;
        vue.y = dimensions.hauteur / 2 - positions[identifiant].y * echelle;
        appliquerVue();
    }

    function toutesLesPositions() {
        return Object.keys(positions).map(Number);
    }

    dessinerEtoiles();
    DONNEES.enRoute.forEach(dessinerTrajet);
    DONNEES.systemes.forEach(dessinerSysteme);
    installerGestes();

    document.getElementById('carte-zoom-avant').addEventListener('click', () => zoomerAuCentre(PAS_ZOOM));
    document.getElementById('carte-zoom-arriere').addEventListener('click', () => zoomerAuCentre(1 / PAS_ZOOM));
    document.getElementById('carte-base').addEventListener('click', () => {
        if (baseConnue()) {
            centrerSur(DONNEES.base, ECHELLE_BASE);
        } else {
            ajuster(toutesLesPositions(), ECHELLE_AJUSTEMENT_MAX);
        }
    });
    document.getElementById('carte-fermer').addEventListener('click', fermerFeuille);
    document.getElementById('carte-annuler').addEventListener('click', annulerOrdre);
    document.addEventListener('keydown', (evenement) => {
        if (evenement.key !== TOUCHE_ECHAP) {
            return;
        }
        if (!feuille.hidden) {
            fermerFeuille();
        } else if (ordre) {
            annulerOrdre();
        }
    });

    if (DONNEES.enRoute.length > 0) {
        boutonTrajets.hidden = false;
        boutonTrajets.textContent = 'Flottes en route (' + DONNEES.enRoute.length + ')';
        boutonTrajets.addEventListener('click', ouvrirTrajets);
    }

    let dimensionsPrecedentes = taille();
    window.addEventListener('resize', () => {
        const dimensions = taille();
        vue.x += (dimensions.largeur - dimensionsPrecedentes.largeur) / 2;
        vue.y += (dimensions.hauteur - dimensionsPrecedentes.hauteur) / 2;
        dimensionsPrecedentes = dimensions;
        appliquerVue();
    });

    ajuster(toutesLesPositions(), ECHELLE_AJUSTEMENT_MAX);
    if (vue.echelle < ECHELLE_LISIBLE && baseConnue()) {
        centrerSur(DONNEES.base, ECHELLE_DEPART);
    }
    if (DONNEES.focus !== null && systemes[DONNEES.focus]) {
        centrerSur(DONNEES.focus, ECHELLE_BASE);
        marquerSelection(DONNEES.focus);
    }
    if (DONNEES.ordre !== null) {
        reprendreOrdre(DONNEES.ordre);
    }
    tic();
    setInterval(tic, MILLISECONDES);
})();
