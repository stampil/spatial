<?php
foreach(Camp::liste() as $ligne){
    $camp = new Camp($ligne->id);
    $adversaires = array();
    foreach($camp->adversaires() as $adversaire){
        array_push($adversaires, htmlspecialchars($adversaire->nom));
    }
?>
<div class="md3-card">
    <h2><?= $camp->getNom() ?><?= $camp->jouable ? '' : ' (non jouable, piloté par l\'IA)' ?></h2>
    <p class="md3-secondary"><?= htmlspecialchars($camp->histoire) ?></p>
    <p class="md3-secondary">Adversaires : <?= implode(', ', $adversaires) ?></p>
</div>
<?php } ?>
