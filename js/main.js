$(function(){
    
    $('[data-countdown]').each(function() {
        var $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function(event) {
            $this.html(event.strftime('%H:%M:%S'));
            if(event.strftime('%H:%M:%S')=='00:00:00'){
                location.href=location.href;
            }
        }) 
    });

    var DELAI_MESSAGE = 8000;
    var MILLISECONDES = 1000;
    var SECONDES_MINUTE = 60;
    var SECONDES_HEURE = 3600;
    var POURCENT = 100;

    $('.messages .md3-alert').each(function() {
        var message = $(this);
        message.on('click', function() {
            message.remove();
        });
        setTimeout(function() {
            message.remove();
        }, DELAI_MESSAGE);
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

    var progressions = $('.jauge[data-restant]');
    if (progressions.length > 0) {
        var debut = Date.now();
        setInterval(function() {
            var ecoule = Math.floor((Date.now() - debut) / MILLISECONDES);
            progressions.each(function() {
                var jauge = $(this);
                var duree = Number(jauge.data('duree'));
                var restant = Math.max(0, Number(jauge.data('restant')) - ecoule);
                var avancement = duree > 0 ? Math.min(POURCENT, Math.max(0, (duree - restant) * POURCENT / duree)) : POURCENT;
                jauge.find('.jauge__remplissage').css('width', avancement + '%');
                jauge.find('.jauge__valeur').text(formaterDuree(restant));
                if (restant === 0 && !jauge.data('termine')) {
                    jauge.data('termine', true);
                    location.reload();
                }
            });
        }, MILLISECONDES);
    }

});
