<?php

require_once __DIR__ . '/Backend/controller/FileController.php';
require_once __DIR__ . '/Backend/controller/UserController.php';
class Router
{
    private static $instance = null;
    private $request;
    private $method;
    private $base_path;
    private $api_path;
    private $pages_path;

    private $current_page;

    private $fc; // FileController
    private $uc; // UserController

    private function __construct()
    {
        $this->request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];

        // Define the paths
        $this->base_path = __DIR__;
        $this->api_path = "{$this->base_path}/Backend/api";
        $this->pages_path = "{$this->base_path}/Frontend/pages";

        $this->fc = new FileController();
        $this->uc = new UserController();

        $this->current_page = '';
    }

    public static function getInstance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Metodo principale per gestire le rotte
    public function handleRequest()
    {
        // Distinzione tra API e pagine
        if (strpos($this->request, '/api/') === 0) {
            $this->handleApiRequest();
        } else {
            $this->handlePageRequest();
        }
    }

    // Gestisce le richieste API
    private function handleApiRequest()
    {
        // Rimuove il prefisso /api/ per ottenere l'endpoint
        $apiRequest = str_replace('/api/', '', $this->request);

        switch ($apiRequest) {
            case 'upload_file':
                $this->fc->upload();
                break;
            case 'download_file':
                $this->fc->downloadFile();
                break;
            case 'show_files':
                $this->fc->showFiles();
                break;
            case 'login':
                $this->uc->login();
                break;
            case 'register':
                $this->uc->register();
                break;
            case 'logout':
                $this->uc->logout();
                break;
            case 'show_users':
                $this->uc->showUsers();
                break;
            case 'change_role':
                $this->uc->changeUserRole();
                break;
            case 'verify_user':
                $this->uc->verifyUser();
                break;
            case 'init_reset_pwd':
                $this->uc->initResetPassword();
                break;
            case 'reset_pwd':
                $this->uc->resetPassword();
                break;
           
            default:
                http_response_code(404);
                echo json_encode(['error' => 'API not found']);
                break;
        }
    }

    // Gestisce le richieste delle pagine
    private function handlePageRequest()
    {
        switch ($this->request) {
            case '/':
            case '':
                $this->current_page = 'homepage';
                require "{$this->pages_path}/homepage.php";
                break;
            case '/login':
                $this->current_page = 'login';
                require "{$this->pages_path}/login.php";
                break;
            case '/register':
                $this->current_page = 'register';
                require "{$this->pages_path}/register.php";
                break;
            case '/upload_file':
                $this->current_page = 'upload_file';
                require "{$this->pages_path}/upload_file.php";
                break;
            case '/download_file':
                $this->current_page = 'download_file';
                require "{$this->pages_path}/download_file.php";
                break;
            case '/dashboard':
                $this->current_page = 'dashboard';
                require "{$this->pages_path}/dashboard.php";
                break;
            case '/logout':
                $this->current_page = 'logout';
                require "{$this->pages_path}/logout.php";
                break;
            case '/verify_user':
                $this->current_page = 'verify_user';
                require "{$this->pages_path}/verify_user.php";
                break;
            case '/forgot_password':
                $this->current_page = 'forgot_password';
                require "{$this->pages_path}/forgot_password.php";
                break;
            case '/reset_password':
                $this->current_page = 'reset_password';
                require "{$this->pages_path}/reset_password.php";
                break;
            default:
                require "{$this->pages_path}/404.php";
                break;
        }
    }

    // Getter per la pagina corrente
    public function getCurrentPage()
    {
        return $this->current_page;
    }
}