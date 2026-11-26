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
$navClasse = isset($Joueur) ? ' md3-top-app-bar__nav--large' : '';
?>
<header class="md3-top-app-bar">
    <div class="md3-top-app-bar__lead">
        <?php if(isset(PAGE_PARENTS[$p])){ ?>
        <a class="md3-icon-link" href="?p=<?= PAGE_PARENTS[$p] ?>" aria-label="Retour">&larr;</a>
        <?php } ?>
        <a class="md3-top-app-bar__title" href="?p=<?= $homePage ?>"><?= APP_TITLE ?></a>
        <?php if(isset($Joueur)){ ?>
        <button type="button" class="md3-icon-link musique" aria-label="<?= MUSIQUE_LABEL ?>" aria-pressed="true"><span class="musique__son"><?= Icone::html('son') ?></span><span class="musique__muet"><?= Icone::html('muet') ?></span></button>
        <audio class="musique__piste" src="<?= MUSIQUE_FICHIER ?>" loop preload="auto"></audio>
        <?php } ?>
    </div>
    <nav class="md3-top-app-bar__nav<?= $navClasse ?>" aria-label="Navigation principale">
        <?php foreach($menuPages as $page => $label){ ?>
        <a class="md3-nav-link<?= $page === $activePage ? ' md3-nav-link--active' : '' ?>" href="?p=<?= $page ?>"<?= $page === $activePage ? ' aria-current="page"' : '' ?>><?= $label ?></a>
        <?php } ?>
        <?php if(isset($Joueur)){ ?>
        <a class="md3-nav-link" href="?p=<?= LOGOUT_PAGE ?>"><?= LOGOUT_LABEL ?></a>
        <?php } ?>
    </nav>
    <?php if(isset($Joueur)){ ?>
    <div class="md3-top-app-bar__compact">
        <details class="md3-menu">
            <summary aria-label="<?= MENU_ICON_LABEL ?>"><?= Icone::html('menu') ?></summary>
            <div class="md3-menu__liste">
                <?php foreach(array_diff_key($menuPages, MENU_BAS) as $page => $label){ ?>
                <a href="?p=<?= $page ?>"><?= $label ?></a>
                <?php } ?>
                <a href="?p=<?= LOGOUT_PAGE ?>"><?= LOGOUT_LABEL ?></a>
            </div>
        </details>
    </div>
    <?php } ?>
</header>
<?php if(isset($Joueur) && count($menuPages) > 0){ ?>
<nav class="md3-navigation-bar" aria-label="Navigation principale">
    <?php foreach(MENU_BAS as $page => $icone){ ?>
    <a class="md3-navigation-bar__item<?= $page === $activePage ? ' md3-navigation-bar__item--active' : '' ?>" href="?p=<?= $page ?>"<?= $page === $activePage ? ' aria-current="page"' : '' ?>>
        <span class="md3-navigation-bar__indicator"><?= Icone::html($icone) ?></span>
        <?= $menuPages[$page] ?>
    </a>
    <?php } ?>
</nav>
<?php } ?>
