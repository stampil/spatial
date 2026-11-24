<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

const PAGE_NAME_PATTERN = '/^[A-Za-z0-9_]+$/';
const LOGOUT_PAGE = 'deconnection';
const PUBLIC_PAGES = array('welcome', 'connection', 'connecter', 'inscription', 'inscrire', LOGOUT_PAGE, '404');
const PASSWORD_MIN_LENGTH = 6;
const CSRF_KEY = 'csrf_token';
const MESSAGES_KEY = 'messages';
const RAPPORTS_KEY = 'rapports';
const CSRF_TOKEN_BYTES = 32;
const APP_TITLE = 'Spatial';
const HOME_PAGE = 'carte';
const GUEST_HOME_PAGE = 'welcome';
const LOGOUT_LABEL = 'Déconnexion';
const MENU_GUEST = array('welcome' => 'Accueil', 'inscription' => 'Inscription', 'connection' => 'Connexion');
const MENU_PLAYER = array('carte' => 'Carte', 'tableauBord' => 'Tableau de bord', 'listeSysteme' => 'Systèmes', 'listePlanete' => 'Planètes', 'listePerso' => 'Persos', 'listeJoueur' => 'Joueurs', 'listeCamp' => 'Camps', 'listeVaisseau' => 'Vaisseaux', 'classement' => 'Classement');
const MENU_BAS = array('carte' => 'map', 'listePlanete' => 'public', 'listeVaisseau' => 'rocket_launch', 'listePerso' => 'groups', 'classement' => 'leaderboard');
const MENU_ICON_LABEL = 'Menu';
const PAGE_PARENTS = array('affichePerso' => 'listePerso', 'afficheSysteme' => 'listeSysteme', 'affichePlanete' => 'listePlanete', 'afficheJoueur' => 'listeJoueur');
