


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

<p><a href="?p=listeSysteme" >lister les systeme</a></p>
<p><a href="?p=listePlanete" >lister les planetes</a></p>
<p><a href="?p=listeperso" >lister les perso</a></p>