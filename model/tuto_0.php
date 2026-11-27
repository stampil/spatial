<section class="panneau">
    <h1 class="titre-vue"><?= Icone::html('mission') ?>Bienvenue</h1>
    <p>Bienvenue <?= $Joueur->getNom(); ?> sur ce jeu 4X (exploration, expansion, exploitation et extermination).</p>
    <p>Dans cette phase de tuto, un nouveau système vient d'être créé spécialement pour vous, le système <?= $Perso->sur_systeme->getNom(); ?>. Ce système deviendra visible des autres joueurs 7 jours après la fin du tuto.</p>
    <p>Votre planète natale : <?= $Perso->sur_planete->getNom(); ?> du système <?= $Perso->sur_systeme->getNom(); ?></p>
    <p>Humm... vous n'avez ni vaisseau, ni troupe, ni planète sous votre commandement...</p>
    <p>Pour construire des troupes et des vaisseaux, il vous faut une planète, il y a deux moyens de conquérir une planète, par la force ou par la diplomatie.</p>
    <p>Votre puissance de frappe étant réduite à vous et vous seul pour l'instant, optons pour la diplomatie.</p>
    <p>Allez dans le menu Persos pour voir votre (premier) personnage.</p>
</section>
