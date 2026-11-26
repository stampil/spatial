<?php $revenu = $Joueur->revenuNet(); $nombrePlanetes = count(Planete::listeGouvernees($Joueur->id)); ?>
<div class="hud" role="status" aria-label="Ressources">
    <span class="hud__element"><?= Icone::html('credits') ?><span class="hud__valeur"><?= number_format($Joueur->credits, 0, ',', ' ') ?></span><span class="hud__nom"><?= __('credit', 'credits', $Joueur->credits) ?></span></span>
    <span class="hud__element<?= $revenu < 0 ? ' hud__element--negatif' : '' ?>"><?= Icone::html('revenu') ?><span class="hud__valeur"><?= sprintf('%+d', $revenu) ?></span><span class="hud__nom">cr/h</span></span>
    <span class="hud__element"><?= Icone::html('planetes') ?><span class="hud__valeur"><?= $nombrePlanetes ?></span><span class="hud__nom"><?= __('planete', 'planetes', $nombrePlanetes) ?></span></span>
    <span class="hud__element"><?= Icone::html('fusee') ?><span class="hud__valeur"><?= Vaisseau::nombreDeplacements($Joueur->id) ?></span><span class="hud__nom">en route</span></span>
</div>
