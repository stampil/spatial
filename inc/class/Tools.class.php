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

    public static function crypte(string $clair): string{
        return password_hash($clair, PASSWORD_DEFAULT);
    }
    
    public static function isValidCsrfToken(mixed $token): bool{
        return is_string($token) && hash_equals($_SESSION[CSRF_KEY], $token);
    }
    
    public static function show_error_html(string $err): void{
        
    ?>
        <div class="md3-alert md3-alert--error" role="alert"><strong>Attention :</strong> <?=htmlspecialchars($err)?></div>
<?php
    }
    
    public static function isFinCompteRebours(?string $quand): bool{
        if($quand === null){
            return true;
        }
        $now = new DateTime('NOW');
        $fin = new DateTime($quand);
        
        $timout = $fin->getTimestamp() - $now->getTimestamp();
        
        return $timout<=0;
        
        
    }
    
    
}
