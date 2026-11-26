<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$type = giveInputString($_POST, 'type');
$nombre = (int) giveInputString($_POST, 'nombre');
$erreur = Vaisseau::embarquer($Joueur, (int) ($_POST['vaisseau'] ?? 0), $type, $nombre);
Tools::addMessage($erreur ?? 'Embarquement : '.$nombre.' × '.Vaisseau::CATALOGUE[$type]['nom'].'.');

?>

<script>
    location.href='?p=listeVaisseau';
</script>
