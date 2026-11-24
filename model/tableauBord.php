


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

<?php
if($Joueur->etape_tuto >= Tuto::ETAPE_USINE){
    $revenu = $Joueur->revenuNet();
    $Camp = $Joueur->getCamp();
?>
<div class="md3-card">
    <h2>Camp : <?= Camp::libelle($Joueur->id_camp) ?></h2>
    <p class="md3-secondary"><?= htmlspecialchars($Camp->histoire) ?></p>
</div>
<p>Crédits : <?= $Joueur->credits ?> (revenu net : <?= sprintf('%+d', $revenu) ?> cr/h)</p>
<p>Vos planètes :
<?php
    foreach(Planete::listeGouvernees($Joueur->id) as $ligne){
        echo (new Planete($ligne->id))->getNom().' ';
    }
?>
</p>
<?php
}
?>

<br>  Actions :

<p><a href="?p=listeSysteme" >Lister les systèmes</a></p>
<p><a href="?p=listePlanete" >Lister les planètes</a></p>
<p><a href="?p=listePerso" >Lister les persos</a></p>