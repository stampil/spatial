<details class="mission"<?= $p === 'tableauBord' ? ' open' : '' ?>>
    <summary><?= Icone::html('mission') ?>Mission <?= $Joueur->etape_tuto - Tuto::ETAPE_USINE + 1 ?>/<?= Joueur::TUTO_END_STEP - Tuto::ETAPE_USINE ?> : <?= htmlspecialchars(Tuto::titre($Joueur)) ?></summary>
    <p class="mission__texte"><?= Tuto::texte($Joueur) ?></p>
    <?php if(in_array($Joueur->etape_tuto, Tuto::ETAPES_A_VALIDER, true)){ ?>
    <form method="POST" action="?p=tutoSuivant">
        <?= csrfField() ?>
        <input type="submit" class="md3-button" value="J'ai compris" data-tuto="<?= Tuto::CLE_COMPRENDRE ?>" />
    </form>
    <?php } ?>
</details>
<?php if(!$Joueur->aide_icones_masquee){ ?>
<details class="mission">
    <summary><?= Icone::html('info') ?>Les icônes du haut</summary>
    <p class="mission__texte"><?= Tuto::texteIcones() ?></p>
    <form class="mission__fermer" method="POST" action="?p=masquerAideIcones">
        <?= csrfField() ?>
        <button type="submit" class="mission__croix" aria-label="Ne plus afficher cette aide" title="Ne plus afficher cette aide">&times;</button>
    </form>
</details>
<?php } ?>
