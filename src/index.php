<?php
    require_once './router.php';
    $current_page = '';
    // Istanzia il router e gestisce la richiesta
    $router = new Router();
    $router->handle();

