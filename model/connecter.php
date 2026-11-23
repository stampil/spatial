<?php

$email = giveInputString($_POST, 'email');
$mdp = giveInputString($_POST, 'mdp');

if($email !== '' && $mdp !== ''){
    $info = Joueur::connecter(
        array(
            'mdp' =>$mdp,
            'email' =>$email,
        )        
    );  
    
    if($info){
        //$j = new Joueur($info->id);
        session_regenerate_id(true);
        $_SESSION['Sid'] = $info->id;
        $Joueur = new Joueur($info->id);
        $ip = giveIP();
        $ips = explode(" ",$Joueur->IPs);
        array_push($ips, $ip);
        $ips = array_unique($ips);
        $ips = implode(' ', $ips);
        $Joueur->IPs = $ips; 
        $Joueur->lastco = date("Y-m-d H:i:s");
        $Joueur->save();

        header('Location: index.php?p=tableauBord');
        exit();
    }
    else{
        header('Location: index.php?p=connection&erreur=Identifiants non reconnus');
        exit();
    }
}

?>