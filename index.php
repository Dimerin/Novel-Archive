<?php
$request = $_SERVER['REQUEST_URI'];
// Definisci la variabile per la pagina attuale
$current_page = '';

// Switch per gestire le diverse richieste
switch ($request) {
    case '/' :
        $current_page = 'homepage';
        require __DIR__ . '/Frontend/pages/homepage.php';
        break;
    case '' :
        $current_page = 'homepage';
        require __DIR__ . '/Frontend/pages/homepage.php';
        break;
    case '/login' :
        $current_page = 'login';
        require __DIR__ . '/Frontend/pages/login.php';
        break;
    case '/register' :
        $current_page = 'register';
        require __DIR__ . '/Frontend/pages/register.php';
        break;
    case '/upload_file' :
        $current_page = 'upload_file';
        require __DIR__ . '/Frontend/pages/upload_file.php';
        break;
    case '/api/upload_file' :
            $current_page = 'upload_file';
            require __DIR__ . '/Backend/api/upload_file.php';
            break;
    default:
        require __DIR__ . '/Frontend/pages/404.php';
        break;
}
?>
