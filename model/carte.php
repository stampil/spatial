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
        <span class="carte__bandeau-texte" id="carte-bandeau-texte"></span>
        <span class="carte__bandeau-suite" id="carte-bandeau-suite">: choisir le système</span>
        <span aria-hidden="true">·</span>
        <?= Vue::commandeIcone('fermer', 'Annuler', 'md3-button--tonal', ' id="carte-annuler"') ?>
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
    <template id="carte-modele-voyager"><?= Vue::commandeIcone('voyager', 'Voyager ici') ?></template>
</div>
<?php
$donnees = Carte::donnees($Joueur);
$donnees['focus'] = isset($_GET['systeme']) ? (int) $_GET['systeme'] : null;
$donnees['ordre'] = Carte::ordreDeLaRequete($Joueur, $_GET);
?>
<script type="application/json" id="carte-donnees"><?= json_encode($donnees, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
