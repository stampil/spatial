<dialog class="popin" data-auto aria-label="<?= htmlspecialchars($info['titre']) ?>">
    <h3><?= htmlspecialchars($info['titre']) ?></h3>
    <p><?= htmlspecialchars($info['texte']) ?></p>
    <form method="dialog"><?= Vue::boutonIcone('fermer', 'Fermer', false, 'md3-button--tonal') ?></form>
</dialog>
