<?php
if(isset($Joueur)){
    $homePage = HOME_PAGE;
    $menuPages = MENU_PLAYER;
    if($Joueur->etape_tuto == 0){
        $menuPages = array();
    }
}
else{
    $homePage = GUEST_HOME_PAGE;
    $menuPages = MENU_GUEST;
}
$activePage = PAGE_PARENTS[$p] ?? $p;
?>
<header class="md3-top-app-bar">
    <div class="md3-top-app-bar__lead">
        <?php if(isset(PAGE_PARENTS[$p])){ ?>
        <a class="md3-icon-link" href="?p=<?= PAGE_PARENTS[$p] ?>" aria-label="Retour">&larr;</a>
        <?php } ?>
        <a class="md3-top-app-bar__title" href="?p=<?= $homePage ?>"><?= APP_TITLE ?></a>
    </div>
    <nav class="md3-top-app-bar__nav" aria-label="Navigation principale">
        <?php foreach($menuPages as $page => $label){ ?>
        <a class="md3-nav-link<?= $page === $activePage ? ' md3-nav-link--active' : '' ?>" href="?p=<?= $page ?>"<?= $page === $activePage ? ' aria-current="page"' : '' ?>><?= $label ?></a>
        <?php } ?>
        <?php if(isset($Joueur) && $Joueur->etape_tuto >= Joueur::TUTO_END_STEP){ ?>
        <span class="md3-chip"><?= number_format($Joueur->credits, 0, ',', ' ') ?> cr</span>
        <?php } ?>
        <?php if(isset($Joueur)){ ?>
        <a class="md3-nav-link" href="?p=<?= LOGOUT_PAGE ?>"><?= LOGOUT_LABEL ?></a>
        <?php } ?>
    </nav>
</header>
