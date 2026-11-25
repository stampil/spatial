<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$planete = new Planete((int) ($_POST['planete'] ?? 0));
$erreur = Defi::lancer($Joueur, $planete, (int) ($_POST['defenseur'] ?? 0));
if($erreur !== null){
    Tools::addMessage($erreur);
}

?>

<script>
    location.href='?p=affichePlanete&id_planete=<?= $planete->id ?>';
</script>
