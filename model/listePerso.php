<h1 class="titre-vue"><?= Icone::html('persos') ?>Persos</h1>
<?php if($Joueur->etape_tuto==1){ ?>
<details class="mission" open>
    <summary><?= Icone::html('mission') ?>Mission : découvrir votre perso</summary>
    <p class="mission__texte">Voici la liste de tous les persos disponibles. Pour l'instant il n'y a que vous, mais vous pourrez recruter par la suite des espions, diplomates et autres. Touchez votre perso ci-dessous pour ouvrir sa fiche : c'est là que vous lancerez la diplomatie.</p>
</details>
<?php } ?>
<div class="fiches">
<?php
foreach(Perso::liste($Joueur) as $ligne){
    $perso = new Perso($ligne->id);
    $couleur = (new Camp($perso->joueur->id_camp))->couleur;
    $etat = Perso::ETATS[$perso->etat()];
?>
    <section class="panneau fiche--lien" style="--accent:<?= htmlspecialchars($couleur) ?>">
        <div class="fiche__tete">
            <div class="avatar" style="--accent:<?= htmlspecialchars($couleur) ?>"><?= Vue::visuel('role', $perso->role, Perso::ROLES[$perso->role]['icone']) ?></div>
            <div>
                <h2 class="fiche__nom"><a href="?p=affichePerso&id=<?= $perso->id ?>"><?= htmlspecialchars($perso->nom) ?></a></h2>
                <div class="pastilles">
                    <?= Vue::pastille($perso->getRole()) ?>
                    <?= Vue::pastille($etat['libelle'], $etat['variante']) ?>
                </div>
            </div>
        </div>
        <?= Vue::jauge('Force', $perso->FO, Perso::MAX_STARS, 'force') ?>
        <?= Vue::jauge('Endurance', $perso->PV, Perso::MAX_STARS, 'endurance') ?>
        <?= Vue::jauge('Diplomatie', $perso->diplomatie, Perso::MAX_STARS, 'diplomatie') ?>
        <?php if($perso->sur_planete !== null){ ?>
        <p class="md3-secondary">Sur <?= $perso->sur_planete->getNom() ?></p>
        <?php } ?>
    </section>
<?php } ?>
</div>
