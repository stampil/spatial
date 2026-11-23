<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

const PAGE_NAME_PATTERN = '/^[A-Za-z0-9_]+$/';
const LOGOUT_PAGE = 'deconnection';
const PUBLIC_PAGES = array('welcome', 'connection', 'connecter', 'inscription', 'inscrire', LOGOUT_PAGE, '404');
const CSRF_KEY = 'csrf_token';
const CSRF_TOKEN_BYTES = 32;
