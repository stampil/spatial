<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$nombres = array();
foreach(array_keys(Vaisseau::CAPACITES_EMBARQUABLES) as $type){
    $nombres[$type] = (int) ($_POST['nombre'][$type] ?? 0);
}
$erreur = Vaisseau::embarquer($Joueur, (int) ($_POST['vaisseau'] ?? 0), $nombres);
Tools::addMessage($erreur ?? 'Embarquement : '.Vaisseau::resume($nombres).'.');

?>

<script>
    location.href='?p=listeVaisseau';
</script>
