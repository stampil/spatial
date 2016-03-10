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
    
    
}
