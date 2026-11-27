<?php
$infos = Vaisseau::CATALOGUE[$vaisseau->type];
$embarques = Vaisseau::embarques((int) $vaisseau->id);
$capacites = array(Vaisseau::TROUPE => Vaisseau::CAPACITE_TROUPES, Vaisseau::CHASSEUR => Vaisseau::CAPACITE_CHASSEURS);
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
            <?= Vue::lienIcone('?p=carte&ordre='.$vaisseau->id_planete.'&vaisseau='.$vaisseau->id, 'voyager', 'Voyager') ?>
            <?php } ?>
            <?php if($peutEmbarquer){ ?>
            <?= Vue::basculeIcone('embarquer', 'embarquer', 'Embarquer', false, 'md3-button--tonal', ' data-tuto="embarquer embarquer-'.$vaisseau->type.'"') ?>
            <?php } ?>
            <?php if(array_sum($embarques) > 0){ ?>
            <form method="POST" action="?p=debarquer">
                <input type="hidden" name="vaisseau" value="<?= $vaisseau->id ?>" />
                <?= csrfField() ?>
                <?= Vue::boutonIcone('decharger', 'Débarquer', false, 'md3-button--tonal', ' data-tuto="decharger"') ?>
            </form>
            <?php } ?>
            <?= Vue::basculeIcone('renommer', 'renommer', 'Renommer', false, 'md3-button--tonal') ?>
        </div>
    </div>
    <?php if($peutEmbarquer){ ?>
    <div class="panneau-action hors-creation" data-panneau="embarquer" hidden>
        <div class="systeme-details__corps">
            <?php foreach($capacites as $typeEmbarque => $cle){ if($infos[$cle] === 0){ continue; } $place = max(0, min($infos[$cle] - ($embarques[$typeEmbarque] ?? 0), $libres[$typeEmbarque] ?? 0)); ?>
            <form method="POST" action="?p=embarquer">
                <input type="hidden" name="vaisseau" value="<?= $vaisseau->id ?>" />
                <input type="hidden" name="type" value="<?= $typeEmbarque ?>" />
                <?= csrfField() ?>
                <div class="ligne-jeu">
                    <span class="ligne-jeu__info"><?= Icone::html(Vaisseau::CATALOGUE[$typeEmbarque]['icone']) ?><?= Vaisseau::CATALOGUE[$typeEmbarque]['nom'] ?></span>
                    <?= Vue::curseur('nombre', min(1, $place), $place, min(1, $place), 'Nombre à embarquer sur '.$vaisseau->nom.' : '.Vaisseau::CATALOGUE[$typeEmbarque]['nom']) ?>
                    <?= Vue::boutonIcone('embarquer', 'Embarquer', false, 'md3-button--tonal', ($place === 0 ? ' disabled' : '').' data-tuto="embarquer-valider embarquer-valider-'.$vaisseau->type.'-'.$typeEmbarque.'"') ?>
                </div>
            </form>
            <?php } ?>
        </div>
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
