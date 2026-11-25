<div class="md3-dialog" role="dialog" aria-modal="true" aria-label="<?= htmlspecialchars($rapport['titre']) ?>" hidden>
    <div class="md3-dialog__fenetre">
        <?= Vue::bandeau('combat') ?>
        <h2><?= htmlspecialchars($rapport['titre']) ?></h2>
        <div class="rapport__barres"></div>
        <ol class="rapport__journal" aria-live="polite"></ol>
        <p class="rapport__bilan" hidden></p>
        <div class="md3-actions">
            <button type="button" class="md3-button md3-button--outlined rapport__passer">Passer</button>
            <button type="button" class="md3-button rapport__fermer" hidden>Fermer</button>
        </div>
    </div>
    <script type="application/json" class="rapport__donnees"><?= json_encode($rapport, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
</div>
