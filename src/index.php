<?php
require_once './router.php';
// Istanzia il router e gestisce la richiesta
$current_page = '';
$router = new Router();
$router->handle();
?>
