window.Occupation = (function() {
    function demarrer(noeud) {
        if (!noeud || noeud.dataset.occupe) {
            return;
        }
        var spinner = document.createElement('span');
        spinner.className = 'bouton__spinner';
        spinner.setAttribute('aria-hidden', 'true');
        spinner.style.color = getComputedStyle(noeud).color;
        noeud.dataset.occupe = '1';
        noeud.setAttribute('aria-busy', 'true');
        if (noeud.tagName === 'INPUT') {
            var enveloppe = document.createElement('span');
            enveloppe.className = 'bouton-attente';
            noeud.parentNode.insertBefore(enveloppe, noeud);
            enveloppe.appendChild(noeud);
            enveloppe.appendChild(spinner);
        } else {
            noeud.appendChild(spinner);
        }
        noeud.classList.add('bouton--attente');
    }

    function liberer(noeud) {
        if (!noeud || !noeud.dataset.occupe) {
            return;
        }
        var enveloppe = noeud.parentNode;
        if (noeud.tagName === 'INPUT') {
            enveloppe.parentNode.insertBefore(noeud, enveloppe);
            enveloppe.remove();
        } else {
            noeud.querySelector('.bouton__spinner').remove();
        }
        noeud.classList.remove('bouton--attente');
        noeud.removeAttribute('aria-busy');
        delete noeud.dataset.occupe;
    }

    return {demarrer: demarrer, liberer: liberer};
})();

window.Requete = (function() {
    var CAUSE_RESEAU = 'échec réseau';
    var CAUSE_STATUT = 'erreur HTTP ';
    var SEPARATEUR_ADRESSE = ' sur ';

    function adresseCourte(adresse) {
        var url = new URL(adresse, location.href);
        return url.pathname + url.search;
    }

    function demander(adresse, options) {
        return fetch(adresse, options).catch(function() {
            throw new Error(CAUSE_RESEAU + SEPARATEUR_ADRESSE + adresseCourte(adresse));
        }).then(function(reponse) {
            if (!reponse.ok) {
                throw new Error(CAUSE_STATUT + reponse.status + SEPARATEUR_ADRESSE + adresseCourte(reponse.url));
            }
            return reponse;
        });
    }

    return {demander: demander};
})();

$(function(){

    var DELAI_MESSAGE = 8000;
    var DELAI_RAFRAICHISSEMENT_MIN = 3000;
    var DELAI_AVANT_RECHARGEMENT = 3000;
    var CAUSE_PAGE = 'page illisible : ';
    var MESSAGE_ECHEC_ACTION_AVANT = 'Action non confirmée (';
    var MESSAGE_ECHEC_ACTION_APRES = ') : vérifiez le résultat avant de recommencer.';
    var MESSAGE_PAGE_NON_RAFRAICHIE_AVANT = 'Action effectuée, mais la page n\'a pas pu être rafraîchie (';
    var MESSAGE_PAGE_NON_RAFRAICHIE_APRES = ') : rechargement…';
    var MILLISECONDES = 1000;
    var SECONDES_MINUTE = 60;
    var SECONDES_HEURE = 3600;
    var POURCENT = 100;
    var SELECTEURS_REMPLACES = ['main.md3-page', '.hud'];
    var derniereMiseAJour = 0;
    var rafraichissementEnCours = null;

    function initialiserPage() {
        $('[data-countdown]').each(function() {
            var $this = $(this), finalDate = $(this).data('countdown');
            $this.countdown(finalDate, function(event) {
                $this.html(event.strftime('%H:%M:%S'));
                if (event.strftime('%H:%M:%S') == '00:00:00') {
                    rafraichir();
                }
            });
        });
        $('.messages .md3-alert').each(function() {
            var message = $(this);
            message.on('click', function() {
                message.remove();
            });
            setTimeout(function() {
                message.remove();
            }, DELAI_MESSAGE);
        });
        $('.jauge[data-restant]').each(function() {
            $(this).data('debut', Date.now());
        });
    }

    function appliquerPage(html) {
        var page = new DOMParser().parseFromString(html, 'text/html');
        SELECTEURS_REMPLACES.forEach(function(selecteur) {
            var ancien = document.querySelector(selecteur);
            var nouveau = page.querySelector(selecteur);
            if (ancien && nouveau) {
                ancien.replaceWith(nouveau);
            }
        });
        initialiserPage();
        document.dispatchEvent(new Event('page-rafraichie'));
    }

    function rafraichir() {
        var attente = derniereMiseAJour + DELAI_RAFRAICHISSEMENT_MIN - Date.now();
        if (attente > 0) {
            setTimeout(rafraichir, attente);
            return Promise.resolve(null);
        }
        if (rafraichissementEnCours) {
            return rafraichissementEnCours;
        }
        rafraichissementEnCours = window.Requete.demander(location.href, {credentials: 'same-origin'}).then(function(reponse) {
            return reponse.text();
        }).then(function(html) {
            try {
                appliquerPage(html);
            } catch (erreur) {
                throw new Error(CAUSE_PAGE + erreur.message);
            }
            return null;
        }).catch(function(erreur) {
            document.dispatchEvent(new Event('sante-verifier'));
            return erreur.message;
        }).finally(function() {
            rafraichissementEnCours = null;
            derniereMiseAJour = Date.now();
        });
        return rafraichissementEnCours;
    }

    function rafraichirMaintenant() {
        return (rafraichissementEnCours || Promise.resolve()).then(function() {
            derniereMiseAJour = 0;
            return rafraichir();
        });
    }

    function rafraichirApresAction() {
        return rafraichirMaintenant().then(function(cause) {
            if (cause === null) {
                return null;
            }
            return rafraichirMaintenant();
        });
    }

    function libererFormulaire(formulaire) {
        $(formulaire).find(':submit').prop('disabled', false);
        window.Occupation.liberer($(formulaire).data('principal'));
        delete formulaire.dataset.occupe;
    }

    function afficherErreur(texte) {
        var conteneur = $('.messages');
        if (conteneur.length === 0) {
            conteneur = $('<div class="messages" role="status" aria-live="polite"></div>').appendTo('main.md3-page');
        }
        var message = $('<div class="md3-alert md3-alert--error" role="alert"></div>').text(texte).appendTo(conteneur);
        message.on('click', function() {
            message.remove();
        });
        setTimeout(function() {
            message.remove();
        }, DELAI_MESSAGE);
    }

    function signalerEchecAction(formulaire, cause) {
        libererFormulaire(formulaire);
        afficherErreur(MESSAGE_ECHEC_ACTION_AVANT + cause + MESSAGE_ECHEC_ACTION_APRES);
    }

    function signalerPageNonRafraichie(cause) {
        afficherErreur(MESSAGE_PAGE_NON_RAFRAICHIE_AVANT + cause + MESSAGE_PAGE_NON_RAFRAICHIE_APRES);
        setTimeout(function() {
            location.reload();
        }, DELAI_AVANT_RECHARGEMENT);
    }

    $(document).on('submit', 'main form[method="POST"]:not([data-navigation])', function(evenement) {
        evenement.preventDefault();
        var formulaire = this;
        if (formulaire.dataset.occupe) {
            return;
        }
        var corps = new FormData(formulaire);
        var declencheur = evenement.originalEvent && evenement.originalEvent.submitter;
        if (declencheur && declencheur.name) {
            corps.append(declencheur.name, declencheur.value);
        }
        var principal = declencheur || $(formulaire).find(':submit').get(0);
        formulaire.dataset.occupe = '1';
        $(formulaire).data('principal', principal);
        $(formulaire).find(':submit').not(principal).prop('disabled', true);
        window.Occupation.demarrer(principal);
        window.Requete.demander(formulaire.getAttribute('action'), {method: 'POST', body: corps, credentials: 'same-origin'}).then(function() {
            return rafraichirApresAction();
        }).then(function(cause) {
            if (cause !== null) {
                signalerPageNonRafraichie(cause);
            }
        }).catch(function(erreur) {
            document.dispatchEvent(new Event('sante-verifier'));
            signalerEchecAction(formulaire, erreur.message);
        });
    });

    $(document).on('input', '.curseur__piste', function() {
        $(this).siblings('.curseur__valeur').text(this.value);
    });

    $(document).on('click', '[data-popin]', function() {
        document.getElementById($(this).data('popin')).showModal();
    });
    $(document).on('click', 'dialog.popin', function(evenement) {
        if (evenement.target === this) {
            this.close();
        }
    });

    function deuxChiffres(valeur) {
        return (valeur < 10 ? '0' : '') + valeur;
    }

    function formaterDuree(secondes) {
        var heures = Math.floor(secondes / SECONDES_HEURE);
        var minutes = Math.floor((secondes % SECONDES_HEURE) / SECONDES_MINUTE);
        var reste = secondes % SECONDES_MINUTE;
        return (heures > 0 ? heures + ':' : '') + deuxChiffres(minutes) + ':' + deuxChiffres(reste);
    }

    setInterval(function() {
        $('.jauge[data-restant]').each(function() {
            var jauge = $(this);
            var ecoule = Math.floor((Date.now() - jauge.data('debut')) / MILLISECONDES);
            var duree = Number(jauge.data('duree'));
            var restant = Math.max(0, Number(jauge.data('restant')) - ecoule);
            var avancement = duree > 0 ? Math.min(POURCENT, Math.max(0, (duree - restant) * POURCENT / duree)) : POURCENT;
            jauge.find('.jauge__remplissage').css('width', avancement + '%');
            jauge.find('.jauge__valeur').text(formaterDuree(restant));
            if (restant === 0 && !jauge.data('termine')) {
                jauge.data('termine', true);
                rafraichir();
            }
        });
    }, MILLISECONDES);

    initialiserPage();

});
