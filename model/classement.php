<h1 class="titre-vue"><?= Icone::html('classement') ?>Classement</h1>
<p class="md3-secondary">But du jeu : conquérir le plus de planètes, avec la plus grosse flotte. Les joueurs sont classés par planètes gouvernées, puis par force de flotte.</p>

<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('trophee') ?>Joueurs</h2>
    <?php foreach(Classement::joueurs() as $rang => $ligne){ $classe = new Joueur($ligne['id']); ?>
    <div class="rang<?= $classe->id === $Joueur->id ? ' rang--moi' : '' ?>" style="--accent:<?= htmlspecialchars((new Camp($classe->id_camp))->couleur) ?>">
        <span class="rang__numero"><?= $rang + 1 ?></span>
        <?= Vue::emblemeCamp($classe->id_camp, 32) ?>
        <span class="rang__nom"><?= $classe->getNom() ?></span>
        <span class="rang__score"><?= $ligne['planetes'] ?> <?= __('planete', 'planetes', $ligne['planetes']) ?><br>flotte <?= $ligne['force'] ?></span>
    </div>
    <?php } ?>
</section>

<section class="panneau">
    <h2 class="panneau__titre"><?= Icone::html('mission') ?>Camps</h2>
    <?php
    $camps = Classement::camps();
    $total = array_sum(array_column($camps, 'planetes'));
    foreach($camps as $rang => $ligne){ $camp = new Camp($ligne['id']);
    ?>
    <div class="rang" style="--accent:<?= htmlspecialchars($camp->couleur) ?>">
        <span class="rang__numero"><?= $rang + 1 ?></span>
        <?= Vue::emblemeCamp($camp->id, 32) ?>
        <span class="rang__nom"><?= htmlspecialchars($camp->nom) ?></span>
        <span class="rang__score"><?= $ligne['planetes'] ?> / <?= $total ?> <?= __('planete', 'planetes', $total) ?></span>
    </div>
    <?php } ?>
</section>
