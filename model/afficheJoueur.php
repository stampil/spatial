<?php
if(!empty($_GET['id_joueur'])){
    $id_joueur = (int) $_GET['id_joueur'];
}
else{
    exit('id_joueur needed');
}

$j = new Joueur($id_joueur);
$camp = $j->getCamp();
$ligne = null;
foreach(Classement::joueurs() as $rang => $candidat){
    if($candidat['id'] === $j->id){
        $ligne = $candidat;
        $position = $rang + 1;
    }
}
?>
<section class="panneau" style="--accent:<?= htmlspecialchars($camp->couleur) ?>">
    <div class="fiche__tete">
        <?= Vue::emblemeCamp($camp->id, 48) ?>
        <div>
            <h1 class="fiche__nom"><?= htmlspecialchars($j->nom) ?></h1>
            <?= Vue::pastille($camp->nom) ?>
        </div>
    </div>
    <p>Classement : <?= $position ?></p>
    <p><?= $ligne['planetes'] ?> <?= __('planete', 'planetes', $ligne['planetes']) ?> · flotte <?= $ligne['force'] ?></p>
    <?php if($j->id === $Joueur->id){ ?>
    <p>Crédits : <?= number_format($j->credits, 0, ',', ' ') ?></p>
    <?php } ?>
</section>
