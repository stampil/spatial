<h1>Classement</h1>
<p class="md3-secondary">But du jeu : conquérir le plus de planètes, avec la plus grosse flotte. Les joueurs sont classés par planètes gouvernées, puis par force de flotte.</p>

<section class="md3-card">
    <h2>Joueurs</h2>
    <?php foreach(Classement::joueurs() as $rang => $ligne){ ?>
    <div class="md3-row">
        <div><strong><?= $rang + 1 ?>.</strong> <?= (new Joueur($ligne['id']))->getNom() ?></div>
        <span class="md3-secondary"><?= $ligne['planetes'] ?> planète(s) · flotte <?= $ligne['force'] ?></span>
    </div>
    <?php } ?>
</section>

<section class="md3-card">
    <h2>Camps</h2>
    <?php foreach(Classement::camps() as $rang => $ligne){ ?>
    <div class="md3-row">
        <div><strong><?= $rang + 1 ?>.</strong> <?= Camp::libelle($ligne['id']) ?></div>
        <span class="md3-secondary"><?= $ligne['planetes'] ?> planète(s)</span>
    </div>
    <?php } ?>
</section>
