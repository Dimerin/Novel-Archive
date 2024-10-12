<?php

require_once __DIR__ . '/Backend/Controller/FileController.php';
require_once __DIR__ . '/Backend/Controller/UserController.php';
class Router
{
    private $request;
    private $method;
    private $base_path;
    private $api_path;
    private $pages_path;

    private $fc; // FileController
    private $uc; // UserController

    public function __construct()
    {
        $this->request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];

        // Define the paths
        $this->base_path = __DIR__;
        $this->api_path = "{$this->base_path}/Backend/api";
        $this->pages_path = "{$this->base_path}/Frontend/pages";

        $this->fc = new FileController();
        $this->uc = new UserController();

    }

    // Metodo principale per gestire le rotte
    public function handle()
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
                //$this->handleApiMethod("{$this->api_path}/upload_file.php");
                $this->fc->upload();
                break;
            case 'download_file':
                //$this->handleApiMethod("{$this->api_path}/download_file.php");
                //$this->fc->handleRequest();
                $this->fc->downloadFile();
                break;
            case 'login':
                $this->uc->login();
                //$this->handleApiMethod("{$this->api_path}/login_handler.php");
                break;
            case 'register':
                $this->uc->register();
                //$this->handleApiMethod("{$this->api_path}/register_handler.php");
                break;
            case 'reset_pwd':
                $this->handleApiMethod("{$this->api_path}/reset_pwd.php");
                break;
            case 'forgot_pwd':
                $this->handleApiMethod("{$this->api_path}/forgot_pwd.php");
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
        global $current_page;
        switch ($this->request) {
            case '/':
            case '':
                $current_page = 'homepage';
                require "{$this->pages_path}/homepage.php";
                break;
            case '/login':
                $current_page = 'login';
                require "{$this->pages_path}/login.php";
                break;
            case '/register':
                $current_page = 'register';
                require "{$this->pages_path}/register.php";
                break;
            case '/upload_file':
                $current_page = 'upload_file';
                require "{$this->pages_path}/upload_file.php";
                break;
            case '/download_file':
                $current_page = 'download_file';
                require "{$this->pages_path}/download_file.php";
                break;
            case '/dashboard':
                $current_page = 'dashboard';
                require "{$this->pages_path}/dashboard.php";
                break;
            default:
                require "{$this->pages_path}/404.php";
                break;
        }
    }

    // Metodo per gestire GET e POST sulle API
    private function handleApiMethod($filePath)
    {
        // Verifica che il file esista e sia accessibile
        if (!file_exists($filePath) || !is_readable($filePath)) {
            http_response_code(404);
            echo json_encode(['error' => 'File not found']);
            return;
        }
        if ($this->method === 'POST') {
            require $filePath;
        } elseif ($this->method === 'GET') {
            require $filePath; // Stessa logica, ma puoi differenziare
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
    }

    // Getter per la pagina corrente
    public function getCurrentPage()
    {
        global $current_page;
        return $current_page;
    }
}
?>