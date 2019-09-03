<?php

if(!empty($_POST['email']) && !empty($_POST['mdp'])){
    $info = Joueur::connecter(
        array(
            'mdp' =>Tools::crypte($_POST['mdp']),
            'email' =>$_POST['email'],
        )        
    );  
    
    if($info && $info->id){
        //$j = new Joueur($info->id);
        $_SESSION['Sid'] = $info->id;
        header('Location: index.php?p=tableauBord');
    }
    else{
        header('Location: index.php?p=connection&erreur=identifiants non reconnus');
    }
}

?>