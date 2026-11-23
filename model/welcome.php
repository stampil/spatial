<?php
if(!empty($_SESSION['Sid'])){
    header('Location: ?p=tableauBord');
    exit();
}
?>
<h1><?= APP_TITLE ?></h1>
<div class="md3-actions">
    <a class="md3-button" href="?p=inscription">Inscription</a>
    <a class="md3-button md3-button--tonal" href="?p=connection">Connexion</a>
</div>
