<?php
$infos = Vaisseau::CATALOGUE[$vaisseau->type];
$embarques = Vaisseau::embarques((int) $vaisseau->id);
$capacites = array(Vaisseau::TROUPE => Vaisseau::CAPACITE_TROUPES, Vaisseau::CHASSEUR => Vaisseau::CAPACITE_CHASSEURS);
?>
<div class="vaisseau-nomme">
    <div class="ligne-jeu">
        <div class="ligne-jeu__info">
            <?= Vue::visuelVaisseau($vaisseau->type, $Joueur->id_camp) ?>
            <div>
                <strong><?= htmlspecialchars($vaisseau->nom) ?></strong>
                <?php foreach($capacites as $typeEmbarque => $cle){ if($infos[$cle] === 0){ continue; } ?>
                <br><span class="md3-secondary"><?= Vaisseau::CATALOGUE[$typeEmbarque]['nom'] ?> <?= $embarques[$typeEmbarque] ?? 0 ?>/<?= $infos[$cle] ?></span>
                <?php } ?>
            </div>
        </div>
        <?php if($avecVoyage){ ?>
        <a class="md3-button" href="?p=carte&ordre=<?= $vaisseau->id_planete ?>&vaisseau=<?= $vaisseau->id ?>">Voyager</a>
        <?php } ?>
    </div>
    <details class="systeme-details">
        <summary>Embarquer</summary>
        <div class="systeme-details__corps">
            <?php foreach($capacites as $typeEmbarque => $cle){ if($infos[$cle] === 0){ continue; } $place = max(0, min($infos[$cle] - ($embarques[$typeEmbarque] ?? 0), $libres[$typeEmbarque] ?? 0)); ?>
            <form method="POST" action="?p=embarquer">
                <input type="hidden" name="vaisseau" value="<?= $vaisseau->id ?>" />
                <input type="hidden" name="type" value="<?= $typeEmbarque ?>" />
                <?= csrfField() ?>
                <div class="ligne-jeu">
                    <span class="ligne-jeu__info"><?= Icone::html(Vaisseau::CATALOGUE[$typeEmbarque]['icone']) ?><?= Vaisseau::CATALOGUE[$typeEmbarque]['nom'] ?></span>
                    <?= Vue::curseur('nombre', min(1, $place), $place, min(1, $place), 'Nombre à embarquer sur '.$vaisseau->nom.' : '.Vaisseau::CATALOGUE[$typeEmbarque]['nom']) ?>
                    <input type="submit" class="md3-button md3-button--tonal" value="Embarquer"<?= $place === 0 ? ' disabled' : '' ?> />
                </div>
            </form>
            <?php } ?>
            <?php if(array_sum($embarques) > 0){ ?>
            <form method="POST" action="?p=debarquer">
                <input type="hidden" name="vaisseau" value="<?= $vaisseau->id ?>" />
                <?= csrfField() ?>
                <input type="submit" class="md3-button md3-button--outlined" value="Débarquer" />
            </form>
            <?php } ?>
        </div>
    </details>
    <details class="systeme-details">
        <summary>Renommer</summary>
        <form class="systeme-details__corps" method="POST" action="?p=renommerVaisseau">
            <input type="hidden" name="vaisseau" value="<?= $vaisseau->id ?>" />
            <?= csrfField() ?>
            <div class="ligne-jeu">
                <input type="text" class="md3-text" name="nom" maxlength="<?= Vaisseau::NOM_LONGUEUR_MAX ?>" value="<?= htmlspecialchars($vaisseau->nom) ?>" required aria-label="Nom du vaisseau" />
                <input type="submit" class="md3-button md3-button--tonal" value="Renommer" />
            </div>
        </form>
    </details>
</div>
