<h1 class="titre-vue"><?= Icone::html('quartier') ?>Quartier général</h1>
<?php
if($Joueur->etape_tuto >= Tuto::ETAPE_USINE){
    $Camp = $Joueur->getCamp();
?>
<section class="panneau" style="--accent:<?= htmlspecialchars($Camp->couleur) ?>">
    <?= Vue::banniereCamp($Camp->id) ?>
    <div class="fiche__tete">
        <?= Vue::emblemeCamp($Camp->id, 48) ?>
        <h2 class="fiche__nom" style="--accent:<?= htmlspecialchars($Camp->couleur) ?>"><?= htmlspecialchars($Camp->nom) ?></h2>
    </div>
    <p class="md3-secondary"><?= htmlspecialchars($Camp->histoire) ?></p>
    <p><strong>Avantage :</strong> <?= htmlspecialchars($Camp->avantage()) ?></p>
</section>

<div class="raccourcis">
    <a class="emplacement" href="?p=carte"><?= Icone::html('carte') ?>Carte</a>
    <a class="emplacement" href="?p=listePlanete"><?= Icone::html('planetes') ?><?= count(Planete::listeGouvernees($Joueur->id)) ?> <?= __('colonie', 'colonies', count(Planete::listeGouvernees($Joueur->id))) ?></a>
    <a class="emplacement" href="?p=listeVaisseau"><?= Icone::html('fusee') ?><?= Vaisseau::nombreDeplacements($Joueur->id) ?> en route</a>
    <a class="emplacement" href="?p=listePerso"><?= Icone::html('persos') ?><?= count(Perso::liste($Joueur)) ?> <?= __('perso', 'persos', count(Perso::liste($Joueur))) ?></a>
</div>

<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('journal') ?>Journal</h2>
    <?php $evenements = Journal::recents($Joueur->id); ?>
    <?php if(count($evenements) === 0){ ?>
    <p class="md3-secondary">Rien à signaler pour l'instant.</p>
    <?php } ?>
    <ul class="journal">
        <?php foreach($evenements as $evenement){ ?>
        <li><span class="journal__date"><?= htmlspecialchars($evenement->creato) ?></span><?= htmlspecialchars($evenement->message) ?></li>
        <?php } ?>
    </ul>
</section>
<?php
}
else{
?>
<section class="panneau">
    <p>Prenez contact avec votre premier personnage pour commencer la partie.</p>
    <a class="md3-button" href="?p=listePerso">Voir mes persos</a>
</section>
<?php
}
