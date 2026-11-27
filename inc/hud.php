<?php $revenu = $Joueur->revenuNet(); $nombrePlanetes = count(Planete::listeGouvernees($Joueur->id)); ?>
<div class="hud" role="status" aria-label="Ressources">
    <span class="hud__element" data-credits="<?= $Joueur->credits ?>" data-reste="<?= $Joueur->credits_reste ?>" data-revenu="<?= $revenu ?>" data-dans="<?= $Joueur->secondesAvantCredits() ?>" data-un="<?= __('credit','credits',1) ?>" data-plusieurs="<?= __('credit','credits',2) ?>"><?= Icone::html('credits') ?><span class="hud__valeur"><?= number_format($Joueur->credits, 0, ',', ' ') ?></span><span class="hud__nom"><?= __('credit', 'credits', $Joueur->credits) ?></span></span>
    <span class="hud__element<?= $revenu < 0 ? ' hud__element--negatif' : '' ?>"><?= Icone::html('revenu') ?><span class="hud__valeur"><?= sprintf('%+d', $revenu) ?></span><span class="hud__nom">cr/h</span></span>
    <span class="hud__element"><?= Icone::html('planetes') ?><span class="hud__valeur"><?= $nombrePlanetes ?></span><span class="hud__nom"><?= __('planete', 'planetes', $nombrePlanetes) ?></span></span>
    <span class="hud__element" data-tuto="<?= Tuto::CLE_EN_ROUTE ?>"><?= Icone::html('fusee') ?><span class="hud__valeur"><?= Vaisseau::nombreDeplacements($Joueur->id) ?></span><span class="hud__nom">en route</span></span>
</div>
