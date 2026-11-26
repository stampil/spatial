<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$erreur = Vaisseau::debarquer($Joueur, (int) ($_POST['vaisseau'] ?? 0));
Tools::addMessage($erreur ?? 'Troupes et chasseurs débarqués sur la planète.');

?>

<script>
    location.href='?p=listeVaisseau';
</script>
