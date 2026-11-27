<?php
$stock = Vaisseau::stock($planete->id, $Joueur->id);
$file = Vaisseau::enFile($planete->id);
$libres = $planete->emplacementsLibres();
$constructions = $planete->constructionsEnCours();
?>
<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('emplacements') ?>Emplacements <?= $planete->batimentsTotal() ?> / <?= $planete->slot ?></h2>
    <div class="emplacements">
        <?php foreach(Batiment::CATALOGUE as $batiment => $infos){ for($i = 0; $i < $planete->nombre($batiment); $i++){ ?>
        <a class="emplacement" href="#bat-<?= $batiment ?>"><?= Icone::html($infos['icone']) ?><?= $infos['court'] ?></a>
        <?php } } ?>
        <?php foreach($constructions as $construction){ for($i = 0; $i < $construction->restants; $i++){ ?>
        <a class="emplacement" href="#bat-<?= $construction->batiment ?>"><?= Icone::html(Batiment::CATALOGUE[$construction->batiment]['icone']) ?><?= Batiment::CATALOGUE[$construction->batiment]['court'] ?> (chantier)</a>
        <?php } } ?>
        <?php for($i = 0; $i < $libres; $i++){ ?>
        <a class="emplacement emplacement--vide" href="#construire"><?= Icone::html('ajouter') ?>Libre</a>
        <?php } ?>
    </div>
    <?php foreach($constructions as $construction){ ?>
    <?= Vue::progression(Batiment::CATALOGUE[$construction->batiment]['nom'].' · '.$construction->restants.' '.__('restant', 'restants', $construction->restants), strtotime($construction->fin) - time(), $planete->dureeProduction($Joueur, Batiment::CATALOGUE[$construction->batiment]['duree']), Batiment::CATALOGUE[$construction->batiment]['icone']) ?>
    <?php } ?>
    <p class="md3-secondary">Revenu net de la planète : <?= sprintf('%+d', $planete->revenuNet()) ?> cr/h</p>
</section>

<section class="panneau" id="construire">
    <h2 class="panneau__titre"><?= Icone::html('construire') ?>Construire</h2>
    <?php foreach(Batiment::CATALOGUE as $batiment => $infos){ ?>
    <div class="ligne-jeu" id="bat-<?= $batiment ?>">
        <div class="ligne-jeu__info">
            <button type="button" class="visuel-bouton" data-popin="popin-<?= $batiment ?>" aria-label="Détails : <?= $infos['nom'] ?>"><?= Icone::html($infos['icone'], Vue::ICONE_STRUCTURE_TAILLE) ?></button>
            <dialog class="popin" id="popin-<?= $batiment ?>">
                <div class="popin__image"><?= Vue::visuel('batiment', $infos['icone'], $infos['icone']) ?></div>
                <h3><?= $infos['nom'] ?></h3>
                <p><?= htmlspecialchars(Batiment::description($batiment)) ?></p>
                <p class="md3-secondary"><?= $infos['prix'] ?> cr · maintenance <?= $infos['maintenance'] ?> cr/h</p>
                <form method="dialog"><?= Vue::boutonIcone('fermer', 'Fermer', false, 'md3-button--tonal') ?></form>
            </dialog>
            <div>
                <strong><?= $infos['nom'] ?></strong> × <?= $planete->nombre($batiment) ?><br>
                <span class="md3-secondary"><?= $infos['prix'] ?> cr · <?= gmdate('i:s', $planete->dureeProduction($Joueur, $infos['duree'])) ?> · maintenance <?= $infos['maintenance'] ?> cr/h</span>
            </div>
        </div>
        <div class="md3-actions">
            <form class="action" method="POST" action="?p=gererBatiment">
                <input type="hidden" name="planete" value="<?= $planete->id ?>" />
                <input type="hidden" name="batiment" value="<?= $batiment ?>" />
                <input type="hidden" name="operation" value="<?= Planete::OPERATION_CONSTRUIRE ?>" />
                <?= csrfField() ?>
                <?php $maximum = min($libres, intdiv($Joueur->credits, $infos['prix'])); ?>
                <?= Vue::curseur('nombre', min(1, $maximum), $maximum, min(1, $maximum), 'Nombre à construire : '.$infos['nom']) ?>
                <?= Vue::boutonIcone('construire', 'Construire', false, '', ($maximum === 0 ? ' disabled' : '').' data-tuto="'.Tuto::CLE_CONSTRUIRE.$batiment.'"') ?>
            </form>
            <form class="action" method="POST" action="?p=gererBatiment">
                <input type="hidden" name="planete" value="<?= $planete->id ?>" />
                <input type="hidden" name="batiment" value="<?= $batiment ?>" />
                <input type="hidden" name="operation" value="<?= Planete::OPERATION_DEMOLIR ?>" />
                <?= csrfField() ?>
                <?= Vue::boutonIcone('demolir', 'Démolir', false, 'md3-button--tonal', $planete->nombre($batiment) > 0 ? '' : ' disabled') ?>
                <span class="md3-secondary action__valeur">+<?= Batiment::remboursement($batiment) ?> cr</span>
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
            <?= Vue::visuel('role', $role, $infosRole['icone']) ?>
            <strong><?= $infosRole['nom'] ?></strong>
        </div>
        <form class="action" method="POST" action="?p=recruter">
            <input type="hidden" name="planete" value="<?= $planete->id ?>" />
            <input type="hidden" name="role" value="<?= $role ?>" />
            <?= csrfField() ?>
            <span class="md3-secondary action__valeur"><?= $Joueur->prixUnite($infosRole['prix']) ?> cr</span>
            <?= Vue::boutonIcone('recruter', 'Recruter : '.$infosRole['nom'], false, '', ' data-tuto="'.Tuto::CLE_RECRUTER.$role.'"') ?>
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
    <?php foreach($file as $ligne){ if(Vaisseau::CATALOGUE[$ligne['type']]['usine'] !== $usine){ continue; } ?>
    <?= Vue::progression(Vaisseau::CATALOGUE[$ligne['type']]['nom'].' · '.$ligne['restants'].' '.__('restant', 'restants', $ligne['restants']), strtotime($ligne['fin']) - time(), $planete->dureeProduction($Joueur, Vaisseau::CATALOGUE[$ligne['type']]['duree']), Vaisseau::CATALOGUE[$ligne['type']]['icone']) ?>
    <?php } ?>
    <?php foreach($types as $type){ $infos = Vaisseau::CATALOGUE[$type]; ?>
    <div class="ligne-jeu">
        <div class="ligne-jeu__info">
            <?= Vue::visuelVaisseau($type, $Joueur->id_camp) ?>
            <div>
                <strong><?= $infos['nom'] ?></strong> × <?= $stock[$type] ?? 0 ?> en stock<br>
                <span class="md3-secondary"><?= $Joueur->prixVaisseau($type) ?> cr · <?= gmdate('i:s', $planete->dureeProduction($Joueur, $infos['duree'])) ?></span>
            </div>
        </div>
        <form method="POST" action="?p=produire">
            <input type="hidden" name="planete" value="<?= $planete->id ?>" />
            <input type="hidden" name="type" value="<?= $type ?>" />
            <?= csrfField() ?>
            <?php $maximum = $type === Vaisseau::TROUPE ? Vaisseau::placeTroupes($planete, $Joueur->id) : intdiv($Joueur->credits, $Joueur->prixVaisseau($type)); ?>
            <?= Vue::curseur('nombre', min(1, $maximum), $maximum, min(1, $maximum), 'Nombre à produire : '.$infos['nom']) ?>
            <?= Vue::boutonIcone('produire', 'Produire', false, 'md3-button--tonal', ($maximum === 0 ? ' disabled' : '').' data-tuto="'.Tuto::CLE_PRODUIRE.$type.'"') ?>
        </form>
    </div>
    <?php } ?>
</section>
<?php } ?>
