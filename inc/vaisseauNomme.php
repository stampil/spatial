<?php
$infos = Vaisseau::CATALOGUE[$vaisseau->type];
$embarques = Vaisseau::embarques((int) $vaisseau->id);
$capacites = Vaisseau::CAPACITES_EMBARQUABLES;
$peutEmbarquer = $infos[Vaisseau::CAPACITE_TROUPES] > 0 || $infos[Vaisseau::CAPACITE_CHASSEURS] > 0;
?>
<div class="vaisseau-nomme" data-bloc>
    <div class="ligne-jeu">
        <div class="ligne-jeu__info">
            <?php if($avecCase){ ?>
            <label class="case-flotte"><input type="checkbox" form="creation-flotte-<?= $vaisseau->id_planete ?>" name="vaisseau[]" value="<?= $vaisseau->id ?>" aria-label="Choisir <?= htmlspecialchars($vaisseau->nom) ?> pour la flotte" /></label>
            <?php } ?>
            <?= Vue::visuelVaisseau($vaisseau->type, $Joueur->id_camp) ?>
            <div>
                <strong><?= htmlspecialchars($vaisseau->nom) ?></strong>
                <?php foreach($capacites as $typeEmbarque => $cle){ if($infos[$cle] === 0){ continue; } ?>
                <br><span class="md3-secondary"><?= Vaisseau::CATALOGUE[$typeEmbarque]['nom'] ?> <?= $embarques[$typeEmbarque] ?? 0 ?>/<?= $infos[$cle] ?></span>
                <?php } ?>
            </div>
        </div>
        <div class="actions-icones hors-creation">
            <?php if($avecVoyage){ ?>
            <?= Vue::lienIcone('?p=carte&ordre='.$vaisseau->id_planete.'&vaisseau='.$vaisseau->id, 'voyager', 'Voyager', false, '', ' data-tuto="'.Tuto::CLE_VOYAGER.$vaisseau->type.'"') ?>
            <?php } ?>
            <?php if($peutEmbarquer){ ?>
            <?= Vue::basculeIcone('embarquer', 'embarquer', 'Embarquer', false, 'md3-button--tonal md3-button--plus', ' data-tuto="embarquer embarquer-'.$vaisseau->type.'"') ?>
            <?php } ?>
            <?php if(array_sum($embarques) > 0){ ?>
            <?= Vue::basculeIcone('debarquer', 'decharger', 'Débarquer', false, 'md3-button--tonal md3-button--moins', ' data-tuto="decharger"') ?>
            <?php } ?>
            <?= Vue::basculeIcone('renommer', 'renommer', 'Renommer', false, 'md3-button--tonal') ?>
        </div>
    </div>
    <?php if($peutEmbarquer){ ?>
    <div class="panneau-action hors-creation" data-panneau="embarquer" hidden>
        <form class="systeme-details__corps" method="POST" action="?p=embarquer">
            <input type="hidden" name="vaisseau" value="<?= $vaisseau->id ?>" />
            <?= csrfField() ?>
            <?php foreach($capacites as $typeEmbarque => $cle){ if($infos[$cle] === 0){ continue; } $place = max(0, min($infos[$cle] - ($embarques[$typeEmbarque] ?? 0), $libres[$typeEmbarque] ?? 0)); ?>
            <div class="ligne-jeu">
                <span class="ligne-jeu__info"><?= Icone::html(Vaisseau::CATALOGUE[$typeEmbarque]['icone']) ?><?= Vaisseau::CATALOGUE[$typeEmbarque]['nom'] ?></span>
                <?= Vue::curseur('nombre['.$typeEmbarque.']', 0, $place, 0, 'Nombre à embarquer sur '.$vaisseau->nom.' : '.Vaisseau::CATALOGUE[$typeEmbarque]['nom']) ?>
            </div>
            <?php } ?>
            <div class="ligne-jeu">
                <?= Vue::boutonIcone('embarquer', 'Embarquer', false, 'md3-button--tonal md3-button--plus', ' data-tuto="embarquer-valider embarquer-valider-'.$vaisseau->type.'"') ?>
            </div>
        </form>
    </div>
    <?php } ?>
    <?php if(array_sum($embarques) > 0){ ?>
    <div class="panneau-action hors-creation" data-panneau="debarquer" hidden>
        <form class="systeme-details__corps" method="POST" action="?p=debarquer">
            <input type="hidden" name="vaisseau" value="<?= $vaisseau->id ?>" />
            <?= csrfField() ?>
            <?php foreach($capacites as $typeEmbarque => $cle){ if($infos[$cle] === 0){ continue; } ?>
            <div class="ligne-jeu">
                <span class="ligne-jeu__info"><?= Icone::html(Vaisseau::CATALOGUE[$typeEmbarque]['icone']) ?><?= Vaisseau::CATALOGUE[$typeEmbarque]['nom'] ?></span>
                <?= Vue::curseur('nombre['.$typeEmbarque.']', 0, $embarques[$typeEmbarque] ?? 0, 0, 'Nombre à débarquer de '.$vaisseau->nom.' : '.Vaisseau::CATALOGUE[$typeEmbarque]['nom']) ?>
            </div>
            <?php } ?>
            <div class="ligne-jeu">
                <?= Vue::boutonIcone('decharger', 'Débarquer', false, 'md3-button--tonal md3-button--moins', ' data-tuto="debarquer-valider"') ?>
            </div>
        </form>
    </div>
    <?php } ?>
    <div class="panneau-action hors-creation" data-panneau="renommer" hidden>
        <form class="systeme-details__corps" method="POST" action="?p=renommerVaisseau">
            <input type="hidden" name="vaisseau" value="<?= $vaisseau->id ?>" />
            <?= csrfField() ?>
            <div class="ligne-jeu">
                <input type="text" class="md3-text" name="nom" maxlength="<?= Vaisseau::NOM_LONGUEUR_MAX ?>" value="<?= htmlspecialchars($vaisseau->nom) ?>" required aria-label="Nom du vaisseau" />
                <?= Vue::boutonIcone('valider', 'Renommer', false, 'md3-button--tonal') ?>
            </div>
        </form>
    </div>
</div>
