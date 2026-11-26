<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

const PAGE_NAME_PATTERN = '/^[A-Za-z0-9_]+$/';
const LOGOUT_PAGE = 'deconnection';
const SANTE_ADRESSE = 'sante.php';
const RECONNEXION_PAGE = 'reconnecter';
const CONNEXION_CLE_STOCKAGE = 'novarche_connexion';
const PUBLIC_PAGES = array('welcome', 'connection', 'connecter', 'inscription', 'inscrire', RECONNEXION_PAGE, LOGOUT_PAGE, '404');
const CSRF_KEY = 'csrf_token';
const MESSAGES_KEY = 'messages';
const RAPPORTS_KEY = 'rapports';
const CSRF_TOKEN_BYTES = 32;
const APP_TITLE = 'Novarche';
const HOME_PAGE = 'carte';
const GUEST_HOME_PAGE = 'welcome';
const LOGOUT_LABEL = 'Déconnexion';
const MENU_GUEST = array('welcome' => 'Accueil', 'inscription' => 'Inscription', 'connection' => 'Connexion');
const MENU_PLAYER = array('carte' => 'Carte', 'tableauBord' => 'Quartier général', 'listePlanete' => 'Colonies', 'listeVaisseau' => 'Flottes', 'listePerso' => 'Persos', 'classement' => 'Classement', 'listeCamp' => 'Camps');
const PAGES_TITRES = MENU_PLAYER + MENU_GUEST + array('afficheSysteme' => 'Système', 'affichePlanete' => 'Planète', 'affichePerso' => 'Perso', 'afficheJoueur' => 'Joueur', 'tuto_0' => 'Bienvenue');
const MENU_BAS = array('carte' => 'carte', 'listePlanete' => 'planetes', 'listeVaisseau' => 'fusee', 'listePerso' => 'persos', 'classement' => 'classement');
const MENU_ICON_LABEL = 'Menu';
const RESET_PAGE = 'reset';
const RESET_NUMERO = 1;
const RESET_LABEL = 'Reset '.RESET_NUMERO;
const RESET_CONFIRMATION = 'Supprimer toutes les planètes, vaisseaux et persos, recréer l\'univers et relancer le tutoriel de chaque compte ? Les comptes sont conservés.';
const MUSIQUE_LABEL = 'Musique d\'ambiance';
const MUSIQUE_FICHIER = 'audio/conquete-spatiale.mp3';
const PAGE_PARENTS = array('afficheSysteme' => 'carte', 'affichePerso' => 'listePerso', 'affichePlanete' => 'listePlanete', 'afficheJoueur' => 'classement');
