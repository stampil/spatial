<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tools
 *
 * @author sgourmand
 */
class Tools {

    private static $cle ='aze%fzg*vbomzeg@_-';
    
    public static function crypte($clair){
        $crypte = crypt($clair,'$6$rounds=5000$'.self::$cle.'$');
        $tab = explode("$",$crypte);
        return  $tab[4];
    }
    
    public static function print_pre($var){
        echo '<pre>';
        echo 'print_pre('.gettype($var).') : ';
        if(is_array($var) || is_object($var)){
            print_r($var);
        }
        else{
            echo $var;
        }
        echo '</pre>';
    }
    
    public static function deploy(){
 
        $bdd= MyPDO::getInstance();
        $bdd->query("CREATE TABLE `user` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `nom` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `mdp` varchar(255) NOT NULL,
            `credits` int(11) NOT NULL DEFAULT '5000',
            `creato` datetime NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`),
            UNIQUE KEY `nom` (`nom`)
           ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

    }
    
    public static function show_error_html($err){
        
    ?>
        <div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		<strong>Attention:</strong> <?=$err?></p>
	</div>
        </div>
<?php
    }
    
    public static function isFinCompteRebours($quand){
        $now = new DateTime('NOW');
        $fin = new DateTime($quand);
        
        $timout = $fin->getTimestamp() - $now->getTimestamp();
        
        return $timout<=0;
        
        
    }
    
    
}
