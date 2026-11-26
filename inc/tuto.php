<details class="mission"<?= $p === 'tableauBord' ? ' open' : '' ?>>
    <summary><?= Icone::html('mission') ?>Mission <?= $Joueur->etape_tuto - Tuto::ETAPE_USINE + 1 ?>/<?= Joueur::TUTO_END_STEP - Tuto::ETAPE_USINE ?> : <?= htmlspecialchars(Tuto::titre($Joueur)) ?></summary>
    <p class="mission__texte"><?= Tuto::texte($Joueur) ?></p>
</details>
<details class="mission">
    <summary><?= Icone::html('info') ?>Les icônes du haut</summary>
    <p class="mission__texte"><?= Tuto::texteIcones() ?></p>
</details>
