<dialog class="popin" data-auto aria-label="<?= htmlspecialchars($info['titre']) ?>">
    <?php if(($info['illustration'] ?? null) !== null){ echo Vue::bandeau($info['illustration']); } ?>
    <h3><?= htmlspecialchars($info['titre']) ?></h3>
    <?php if(($info['id_camp'] ?? null) !== null){ ?>
    <p class="popin__camp"><?= Vue::emblemeCamp($info['id_camp'], Vue::ICONE_VAISSEAU_TAILLE) ?><?= htmlspecialchars((new Camp($info['id_camp']))->nom) ?></p>
    <?php } ?>
    <p><?= htmlspecialchars($info['texte']) ?></p>
    <form method="dialog"><?= Vue::boutonIcone('fermer', 'Fermer', false, 'md3-button--tonal') ?></form>
</dialog>
