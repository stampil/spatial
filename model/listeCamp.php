<h1 class="titre-vue"><?= Icone::html('mission') ?>Camps</h1>
<div class="fiches">
<?php
$comptes = array();
foreach(Classement::camps() as $ligne){
    $comptes[$ligne['id']] = $ligne['planetes'];
}
$total = array_sum($comptes);
foreach(Camp::liste() as $ligne){
    $camp = new Camp($ligne->id);
    $adversaires = array();
    foreach($camp->adversaires() as $adversaire){
        array_push($adversaires, htmlspecialchars($adversaire->nom));
    }
?>
<section class="panneau" style="--accent:<?= htmlspecialchars($camp->couleur) ?>">
    <?= Vue::banniereCamp($camp->id) ?>
    <div class="fiche__tete">
        <?= Vue::emblemeCamp($camp->id, 48) ?>
        <div>
            <h2 class="fiche__nom"><?= htmlspecialchars($camp->nom) ?></h2>
            <?= Vue::pastille($camp->jouable ? 'Jouable' : 'Piloté par l\'IA', $camp->jouable ? 'ok' : '') ?>
        </div>
    </div>
    <p class="md3-secondary"><?= htmlspecialchars($camp->histoire) ?></p>
    <p><strong>Avantage :</strong> <?= htmlspecialchars($camp->avantage()) ?></p>
    <?= Vue::jauge('Planètes', $comptes[$camp->id], $total, 'planetes') ?>
    <p class="md3-secondary">Adversaires : <?= implode(', ', $adversaires) ?></p>
</section>
<?php } ?>
</div>
