$(function(){

    var DELAI_MESSAGE = 8000;
    var DELAI_RAFRAICHISSEMENT_MIN = 3000;
    var MESSAGE_ECHEC_ACTION = 'Le serveur n\'a pas répondu : vérifiez le résultat avant de recommencer.';
    var MILLISECONDES = 1000;
    var SECONDES_MINUTE = 60;
    var SECONDES_HEURE = 3600;
    var POURCENT = 100;
    var SELECTEURS_REMPLACES = ['main.md3-page', '.hud'];
    var derniereMiseAJour = 0;
    var miseAJourEnCours = false;

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

    function rafraichir() {
        var attente = derniereMiseAJour + DELAI_RAFRAICHISSEMENT_MIN - Date.now();
        if (attente > 0) {
            setTimeout(rafraichir, attente);
            return Promise.resolve(true);
        }
        if (miseAJourEnCours) {
            return Promise.resolve(true);
        }
        miseAJourEnCours = true;
        return fetch(location.href, {credentials: 'same-origin'}).then(function(reponse) {
            if (!reponse.ok) {
                throw new Error('statut');
            }
            return reponse.text();
        }).then(function(html) {
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
            return true;
        }).catch(function() {
            document.dispatchEvent(new Event('sante-verifier'));
            return false;
        }).finally(function() {
            miseAJourEnCours = false;
            derniereMiseAJour = Date.now();
        });
    }

    function signalerEchecAction(formulaire) {
        $(formulaire).find(':submit').prop('disabled', false);
        var conteneur = $('.messages');
        if (conteneur.length === 0) {
            conteneur = $('<div class="messages" role="status" aria-live="polite"></div>').appendTo('main.md3-page');
        }
        var message = $('<div class="md3-alert md3-alert--error" role="alert"></div>').text(MESSAGE_ECHEC_ACTION).appendTo(conteneur);
        message.on('click', function() {
            message.remove();
        });
        setTimeout(function() {
            message.remove();
        }, DELAI_MESSAGE);
    }

    $(document).on('submit', 'main form[method="POST"]:not([data-navigation])', function(evenement) {
        evenement.preventDefault();
        var formulaire = this;
        var corps = new FormData(formulaire);
        var declencheur = evenement.originalEvent && evenement.originalEvent.submitter;
        if (declencheur && declencheur.name) {
            corps.append(declencheur.name, declencheur.value);
        }
        $(formulaire).find(':submit').prop('disabled', true);
        fetch(formulaire.action, {method: 'POST', body: corps, credentials: 'same-origin'}).then(function(reponse) {
            if (!reponse.ok) {
                throw new Error('statut');
            }
            derniereMiseAJour = 0;
            return rafraichir();
        }).then(function(reussi) {
            if (!reussi) {
                signalerEchecAction(formulaire);
            }
        }).catch(function() {
            document.dispatchEvent(new Event('sante-verifier'));
            signalerEchecAction(formulaire);
        });
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
