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

});

