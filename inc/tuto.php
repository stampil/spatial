<details class="mission"<?= $p === 'tableauBord' ? ' open' : '' ?>>
    <summary><?= Icone::html('mission') ?>Mission <?= $Joueur->etape_tuto - Tuto::ETAPE_USINE + 1 ?>/<?= Joueur::TUTO_END_STEP - Tuto::ETAPE_USINE ?> : <?= htmlspecialchars(Tuto::titre($Joueur)) ?></summary>
    <p class="mission__texte"><?= Tuto::texte($Joueur) ?></p>
    <?php if(in_array($Joueur->etape_tuto, Tuto::ETAPES_A_VALIDER, true)){ ?>
    <form method="POST" action="?p=tutoSuivant">
        <?= csrfField() ?>
        <input type="submit" class="md3-button" value="J'ai compris" />
    </form>
    <?php } ?>
</details>
<details class="mission">
    <summary><?= Icone::html('info') ?>Les icônes du haut</summary>
    <p class="mission__texte"><?= Tuto::texteIcones() ?></p>
</details>
