<div class="carte" id="carte" data-jeton="<?= htmlspecialchars($_SESSION[CSRF_KEY]) ?>">
    <?= Vue::fond('carte') ?>
    <svg class="carte__svg" role="group" aria-label="Carte galactique"></svg>
    <div class="carte__commandes">
        <button type="button" class="carte__commande" id="carte-zoom-avant" aria-label="Zoom avant">+</button>
        <button type="button" class="carte__commande" id="carte-zoom-arriere" aria-label="Zoom arrière">&minus;</button>
        <button type="button" class="carte__commande" id="carte-base">Base</button>
    </div>
    <button type="button" class="carte__trajets" id="carte-trajets" hidden></button>
    <div class="carte__bandeau" id="carte-bandeau" hidden role="status">
        <span id="carte-bandeau-texte"></span>
        <span class="md3-actions">
            <button type="button" class="md3-button md3-button--tonal" id="carte-explorer" hidden>Explorer</button>
            <button type="button" class="md3-button md3-button--outlined" id="carte-annuler">Annuler</button>
        </span>
    </div>
    <aside class="carte__feuille" id="carte-feuille" hidden role="dialog" aria-labelledby="carte-feuille-titre">
        <div class="carte__poignee" aria-hidden="true"></div>
        <header class="carte__feuille-tete">
            <div>
                <h2 id="carte-feuille-titre"></h2>
                <p class="md3-secondary" id="carte-feuille-sous-titre"></p>
            </div>
            <button type="button" class="carte__fermer" id="carte-fermer" aria-label="Fermer">&times;</button>
        </header>
        <div class="carte__feuille-corps" id="carte-feuille-corps"></div>
    </aside>
</div>
<?php
$donnees = Carte::donnees($Joueur);
$donnees['focus'] = isset($_GET['systeme']) ? (int) $_GET['systeme'] : null;
$donnees['ordre'] = Carte::ordreDeLaRequete($_GET);
?>
<script type="application/json" id="carte-donnees"><?= json_encode($donnees, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
