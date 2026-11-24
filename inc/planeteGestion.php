<section class="md3-card">
    <h2>Bâtiments</h2>
    <p>Emplacements : <?= $planete->batimentsTotal() ?> / <?= $planete->slot ?> · Revenu net de la planète : <?= sprintf('%+d', $planete->revenuNet()) ?> cr/h · Crédits : <?= $Joueur->credits ?></p>
    <?php foreach(Batiment::CATALOGUE as $batiment => $infos){ ?>
    <div class="md3-row">
        <div>
            <strong><?= $infos['nom'] ?></strong> × <?= $planete->nombre($batiment) ?><br>
            <span class="md3-secondary"><?= $infos['prix'] ?> cr · maintenance <?= $infos['maintenance'] ?> cr/h</span>
        </div>
        <div class="md3-actions">
            <form method="POST" action="?p=gererBatiment">
                <input type="hidden" name="planete" value="<?= $planete->id ?>" />
                <input type="hidden" name="batiment" value="<?= $batiment ?>" />
                <input type="hidden" name="operation" value="<?= Planete::OPERATION_CONSTRUIRE ?>" />
                <?= csrfField() ?>
                <input type="submit" value="Construire"<?= $planete->batimentsTotal() < $planete->slot ? '' : ' disabled' ?> />
            </form>
            <form method="POST" action="?p=gererBatiment">
                <input type="hidden" name="planete" value="<?= $planete->id ?>" />
                <input type="hidden" name="batiment" value="<?= $batiment ?>" />
                <input type="hidden" name="operation" value="<?= Planete::OPERATION_DEMOLIR ?>" />
                <?= csrfField() ?>
                <input type="submit" class="md3-button md3-button--outlined" value="Démolir (+<?= Batiment::remboursement($batiment) ?> cr)"<?= $planete->nombre($batiment) > 0 ? '' : ' disabled' ?> />
            </form>
        </div>
    </div>
    <?php } ?>
</section>
<?php if($planete->nombre(Batiment::ACADEMIE) > 0){ ?>
<section class="md3-card">
    <h2>Académie</h2>
    <div class="md3-actions">
        <?php foreach(Perso::ROLES as $role => $infosRole){ ?>
        <form method="POST" action="?p=recruter">
            <input type="hidden" name="planete" value="<?= $planete->id ?>" />
            <input type="hidden" name="role" value="<?= $role ?>" />
            <?= csrfField() ?>
            <input type="submit" value="Recruter : <?= $infosRole['nom'] ?> (<?= $Joueur->prixUnite($infosRole['prix']) ?> cr)" />
        </form>
        <?php } ?>
    </div>
</section>
<?php } ?>
<?php
$stock = Vaisseau::stock($planete->id, $Joueur->id);
$productions = Vaisseau::enProduction($planete->id);
foreach(Batiment::CATALOGUE as $usine => $infosUsine){
    $types = Vaisseau::typesDeLUsine($usine);
    if(count($types) === 0 || $planete->nombre($usine) < 1){
        continue;
    }
?>
<section class="md3-card">
    <h2><?= $infosUsine['nom'] ?> × <?= $planete->nombre($usine) ?> (<?= Vaisseau::nombreEnProduction($planete->id, $usine) ?> en production)</h2>
    <?php foreach($types as $type){ $infos = Vaisseau::CATALOGUE[$type]; ?>
    <div class="md3-row">
        <div>
            <strong><?= $infos['nom'] ?></strong> × <?= $stock[$type] ?? 0 ?> en stock<br>
            <span class="md3-secondary"><?= $Joueur->prixUnite($infos['prix']) ?> cr · <?= gmdate('i:s', $infos['duree']) ?></span>
        </div>
        <form method="POST" action="?p=produire">
            <input type="hidden" name="planete" value="<?= $planete->id ?>" />
            <input type="hidden" name="type" value="<?= $type ?>" />
            <?= csrfField() ?>
            <input type="submit" class="md3-button md3-button--tonal" value="Produire" />
        </form>
    </div>
    <?php } ?>
    <?php foreach($productions as $production){ if(Vaisseau::CATALOGUE[$production->type]['usine'] !== $usine){ continue; } ?>
    <p class="md3-secondary"><?= Vaisseau::CATALOGUE[$production->type]['nom'] ?> : prêt dans <span data-countdown="<?= htmlspecialchars($production->fin) ?>"></span></p>
    <?php } ?>
</section>
<?php } ?>
