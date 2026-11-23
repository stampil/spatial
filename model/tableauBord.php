


<?php
$etape = $Joueur->etape_tuto;
switch($etape){
    case '0':
        include 'tuto_0.php';
        
        break;
    case '1':
        break;
    
}

?>

<br>  Actions :

<p><a href="?p=listeSysteme" >Lister les systèmes</a></p>
<p><a href="?p=listePlanete" >Lister les planètes</a></p>
<p><a href="?p=listePerso" >Lister les persos</a></p>