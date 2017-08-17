
<?php

$systemes = Systeme::liste();
    
foreach($systemes as $s){
?>
<p> <a href="?p=afficheSysteme&id_systeme=<?php echo $s->id ?>"><?php echo $s->nom?$s->nom:'Systeme '.$s->id ?></a></p>

<?php } ?>




