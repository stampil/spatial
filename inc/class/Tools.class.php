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
    
    const DATE_FORMAT = 'Y-m-d H:i:s';

    public static function formatDate(int $timestamp): string{
        return date(self::DATE_FORMAT, $timestamp);
    }
    
    public static function now(): string{
        return self::formatDate(time());
    }
    
    public static function nowPlus(int $seconds): string{
        return self::formatDate(time() + $seconds);
    }
    
    public static function addMessage(string $message): void{
        $_SESSION[MESSAGES_KEY][] = $message;
        if(!empty($_SESSION['Sid'])){
            Journal::ajouter((int) $_SESSION['Sid'], $message);
        }
    }
    
    public static function takeMessages(): array{
        $messages = $_SESSION[MESSAGES_KEY] ?? array();
        unset($_SESSION[MESSAGES_KEY]);
        return $messages;
    }
    
    public static function addRapport(array $rapport): void{
        $_SESSION[RAPPORTS_KEY][] = $rapport;
    }
    
    public static function takeRapports(): array{
        $rapports = $_SESSION[RAPPORTS_KEY] ?? array();
        unset($_SESSION[RAPPORTS_KEY]);
        return $rapports;
    }
    
    public static function isValidCsrfToken(mixed $token): bool{
        return is_string($token) && hash_equals($_SESSION[CSRF_KEY], $token);
    }
    
    public static function show_error_html(string $err): void{
        
    ?>
        <div class="md3-alert md3-alert--error" role="alert"><strong>Attention :</strong> <?=htmlspecialchars($err)?></div>
<?php
    }
    
    public static function show_info_html(string $message): void{
    ?>
        <div class="md3-alert md3-alert--info" role="status"><?=htmlspecialchars($message)?></div>
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
