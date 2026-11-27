<div class="md3-dialog" role="dialog" aria-modal="true" aria-label="<?= htmlspecialchars($rapport['titre']) ?>" hidden>
    <div class="md3-dialog__fenetre">
        <?php $illustration = $rapport['illustration'] ?? Combat::ILLUSTRATION_SPATIALE; ?>
        <?= Vue::bandeau(is_file(Vue::DOSSIER_IMAGES.'/'.$illustration.'.'.Vue::EXTENSION_FOND) ? $illustration : Combat::ILLUSTRATION_SPATIALE) ?>
        <h2><?= htmlspecialchars($rapport['titre']) ?></h2>
        <div class="rapport__barres"></div>
        <ol class="rapport__journal" aria-live="polite"></ol>
        <p class="rapport__bilan" hidden></p>
        <?php if(isset($rapport['id_camp'])){ ?>
        <p class="rapport__camp" hidden><?= Vue::emblemeCamp($rapport['id_camp'], Vue::ICONE_VAISSEAU_TAILLE) ?><?= htmlspecialchars((new Camp($rapport['id_camp']))->nom) ?></p>
        <?php } ?>
        <div class="md3-actions">
            <?= Vue::commandeIcone('resoudre', 'Résoudre', 'rapport__resoudre') ?>
            <?= Vue::commandeIcone('passer', 'Passer', 'md3-button--tonal rapport__passer', ' hidden') ?>
            <?= Vue::commandeIcone('fermer', 'Fermer', 'rapport__fermer', ' hidden') ?>
        </div>
    </div>
    <script type="application/json" class="rapport__donnees"><?= json_encode($rapport, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
</div>
