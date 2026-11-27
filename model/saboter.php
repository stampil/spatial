<?php
if(!Tools::isValidCsrfToken($_POST[CSRF_KEY] ?? null)){
    exit('Jeton invalide');
}
$perso = new Perso((int) ($_POST['perso'] ?? 0));
if($Joueur->id !== $perso->joueur->id){
    exit("Ce perso ne vous appartient pas");
}
$erreur = $perso->saboter(giveInputString($_POST, 'batiment'));
if($erreur !== null){
    Tools::addMessage($erreur);
}

?>

<script>
    location.href='?p=affichePerso&id=<?= $perso->id ?>';
</script>
