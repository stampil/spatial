<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$vaisseaux = is_array($_POST['vaisseau'] ?? null) ? $_POST['vaisseau'] : array();

$erreur = Flotte::creer($Joueur, $planete, $vaisseaux);
Tools::addMessage($erreur ?? 'Flotte créée sur '.$planete->nom.'.');

?>

<script>
    location.href='?p=listeVaisseau';
</script>
