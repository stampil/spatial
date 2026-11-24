<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$assassin = new Perso((int) ($_POST['perso'] ?? 0));
$cible = new Perso((int) ($_POST['cible'] ?? 0));

if($Joueur->id !== $assassin->joueur->id){
    exit("Ce perso ne vous appartient pas");
}
$erreur = $assassin->assassiner($cible);
if($erreur !== null){
    Tools::addMessage($erreur);
}

?>

<script>
    location.href='?p=affichePerso&id=<?= $assassin->id ?>';
</script>
