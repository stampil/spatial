<?php

if(!empty($_POST['email']) && !empty($_POST['mdp'])){
    $info = Joueur::connecter(
        array(
            'mdp' =>Tools::crypte($_POST['mdp']),
            'email' =>$_POST['email'],
        )        
    );  
    
    if($info && $info->id){
        echo 'joueur trouvé: '.$info->id;
        $j = new Joueur();
        $j->load($info->id);
        $_SESSION['Sid'] = $info->id;
    }
    else{
        echo 'joueur non trouvé';
    }
}

?>