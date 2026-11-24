<?php
$stock = Vaisseau::stock($planete->id, $Joueur->id);
$productions = Vaisseau::enProduction($planete->id);
$libres = $planete->slot - $planete->batimentsTotal();
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('emplacements') ?>Emplacements <?= $planete->batimentsTotal() ?> / <?= $planete->slot ?></h2>
    <div class="emplacements">
        <?php foreach(Batiment::CATALOGUE as $batiment => $infos){ for($i = 0; $i < $planete->nombre($batiment); $i++){ ?>
        <a class="emplacement" href="#bat-<?= $batiment ?>"><?= Icone::html($infos['icone']) ?><?= $infos['court'] ?></a>
        <?php } } ?>
        <?php for($i = 0; $i < $libres; $i++){ ?>
        <a class="emplacement emplacement--vide" href="#construire"><?= Icone::html('ajouter') ?>Libre</a>
        <?php } ?>
    </div>
    <p class="md3-secondary">Revenu net de la planète : <?= sprintf('%+d', $planete->revenuNet()) ?> cr/h</p>
</section>

<section class="panneau" id="construire">
    <h2 class="panneau__titre"><?= Icone::html('construire') ?>Construire</h2>
    <?php foreach(Batiment::CATALOGUE as $batiment => $infos){ ?>
    <div class="ligne-jeu" id="bat-<?= $batiment ?>">
        <div class="ligne-jeu__info">
            <?= Icone::html($infos['icone']) ?>
            <div>
                <strong><?= $infos['nom'] ?></strong> × <?= $planete->nombre($batiment) ?><br>
                <span class="md3-secondary"><?= $infos['prix'] ?> cr · maintenance <?= $infos['maintenance'] ?> cr/h</span>
            </div>
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
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('academie') ?>Académie</h2>
    <?php foreach(Perso::ROLES as $role => $infosRole){ ?>
    <div class="ligne-jeu">
        <div class="ligne-jeu__info">
            <?= Icone::html($infosRole['icone']) ?>
            <strong><?= $infosRole['nom'] ?></strong>
        </div>
        <form method="POST" action="?p=recruter">
            <input type="hidden" name="planete" value="<?= $planete->id ?>" />
            <input type="hidden" name="role" value="<?= $role ?>" />
            <?= csrfField() ?>
            <input type="submit" value="Recruter : <?= $infosRole['nom'] ?> (<?= $Joueur->prixUnite($infosRole['prix']) ?> cr)" />
        </form>
    </div>
    <?php } ?>
</section>
<?php } ?>
<?php
foreach(Batiment::CATALOGUE as $usine => $infosUsine){
    $types = Vaisseau::typesDeLUsine($usine);
    if(count($types) === 0 || $planete->nombre($usine) < 1){
        continue;
    }
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html($infosUsine['icone']) ?><?= $infosUsine['nom'] ?> × <?= $planete->nombre($usine) ?> (<?= Vaisseau::nombreEnProduction($planete->id, $usine) ?> en production)</h2>
    <?php foreach($productions as $production){ if(Vaisseau::CATALOGUE[$production->type]['usine'] !== $usine){ continue; } ?>
    <?= Vue::progression(Vaisseau::CATALOGUE[$production->type]['nom'], strtotime($production->fin) - time(), $Joueur->dureeEffective(Vaisseau::CATALOGUE[$production->type]['duree']), Vaisseau::CATALOGUE[$production->type]['icone']) ?>
    <?php } ?>
    <?php foreach($types as $type){ $infos = Vaisseau::CATALOGUE[$type]; ?>
    <div class="ligne-jeu">
        <div class="ligne-jeu__info">
            <?= Icone::html($infos['icone']) ?>
            <div>
                <strong><?= $infos['nom'] ?></strong> × <?= $stock[$type] ?? 0 ?> en stock<br>
                <span class="md3-secondary"><?= $Joueur->prixVaisseau($type) ?> cr · <?= gmdate('i:s', $Joueur->dureeEffective($infos['duree'])) ?></span>
            </div>
        </div>
        <form method="POST" action="?p=produire">
            <input type="hidden" name="planete" value="<?= $planete->id ?>" />
            <input type="hidden" name="type" value="<?= $type ?>" />
            <?= csrfField() ?>
            <input type="submit" class="md3-button md3-button--tonal" value="Produire" />
        </form>
    </div>
    <?php } ?>
</section>
<?php } ?>
