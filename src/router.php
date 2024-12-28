<?php

require_once __DIR__ . '/Backend/controller/FileController.php';
require_once __DIR__ . '/Backend/controller/UserController.php';
class Router
{
    private static $instance = null;
    private $request;
    private $base_path;
    private $api_path;
    private $pages_path;

    private $current_page;

    private $fc; // FileController
    private $uc; // UserController

    private function __construct()
    {
        $this->initSecureSession();

        $this->request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

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
        
        // Mappatura degli endpoint API ai metodi corrispondenti e ai permessi richiesti
        $apiEndpoints = [
            'upload_file' => ['handler' => [$this->fc, 'upload'], 'auth' => 'authenticated'],
            'download_file' => ['handler' => [$this->fc, 'downloadFile'], 'auth' => 'authenticated'],
            'show_files' => ['handler' => [$this->fc, 'showFiles'], 'auth' => 'authenticated'],
            'login' => ['handler' => [$this->uc, 'login'], 'auth' => 'unauthenticated'],
            'register' => ['handler' => [$this->uc, 'register'], 'auth' => 'unauthenticated'],
            'logout' => ['handler' => [$this->uc, 'logout'], 'auth' => 'authenticated'],
            'show_users' => ['handler' => [$this->uc, 'showUsers'], 'auth' => 'admin'],
            'change_role' => ['handler' => [$this->uc, 'changeUserRole'], 'auth' => 'admin'],
            'verify_user' => ['handler' => [$this->uc, 'verifyUser'], 'auth' => 'unauthenticated'],
            'forgot_pwd' => ['handler' => [$this->uc, 'forgotPassword'], 'auth' => 'unauthenticated'],
            'reset_pwd' => ['handler' => [$this->uc, 'resetPassword'], 'auth' => 'unauthenticated'],
        ];
    
        // Controlla se l'endpoint esiste nella mappatura
        if (!array_key_exists($apiRequest, $apiEndpoints)) {
            http_response_code(404);
            echo json_encode(['error' => 'API not found']);
            return;
        }
    
        // Recupera le informazioni sull'endpoint
        $endpoint = $apiEndpoints[$apiRequest];
        
        // Verifica i permessi dell'endpoint
        if ($endpoint['auth'] === 'admin' && !$this->isAdmin()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        if ($endpoint['auth'] === 'authenticated' && !$this->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        if ($endpoint['auth'] === 'unauthenticated' && $this->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
    
        // Chiama il metodo corrispondente all'endpoint
        call_user_func($endpoint['handler']);
    }

    // Gestisce le richieste delle pagine
    private function handlePageRequest()
    {   
        // Mappatura dei percorsi alle pagine e requisiti di autenticazione
        $pages = [
            '' => ['page' => 'homepage', 'auth' => 'unauthenticated'],
            '/' => ['page' => 'homepage', 'auth' => 'unauthenticated'],
            '/login' => ['page' => 'login', 'auth' => 'unauthenticated'],
            '/register' => ['page' => 'register', 'auth' => 'unauthenticated'],
            '/dashboard' => ['page' => 'dashboard', 'auth' => 'authenticated'],
            '/logout' => ['page' => 'logout', 'auth' => 'unauthenticated'], // chi ci puo accedere alla pagina?
            '/verify_user' => ['page' => 'verify_user', 'auth' => 'unauthenticated'],
            '/forgot_password' => ['page' => 'forgot_password', 'auth' => 'unauthenticated'],
            '/reset_password' => ['page' => 'reset_password', 'auth' => 'unauthenticated'],
        ];

        // Controllo se il percorso non esiste nella mappatura
        if (!array_key_exists($this->request, $pages)) {
            // Carica la pagina 404 se il percorso non esiste
            require "{$this->pages_path}/404.php";
            return;
        }

        $pageInfo = $pages[$this->request];

        // Controllo dei requisiti di autenticazione
        if ($pageInfo['auth'] === 'authenticated' && !$this->isAuthenticated()) {
            header("Location: /login");
            exit();
        }

        if ($pageInfo['auth'] === 'unauthenticated' && $this->isAuthenticated()) {
            header('Location: /dashboard');
            exit();
        }

        // Imposta la pagina corrente e richiede il file della pagina
        $this->current_page = $pageInfo['page'];
        require "{$this->pages_path}/{$this->current_page}.php";
    }

    private function isAuthenticated()
    {
        return isset($_SESSION['username']);
    }

    private function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    private function initSecureSession(){
        if( session_status() == PHP_SESSION_NONE ){
            session_start(
                [
                    'cookie_lifetime' => 0, // La sessione scade alla chiusura del browser
                    'cookie_httponly' => true,
                    'cookie_secure' => true, // Solo su HTTPS
                    'cookie_samesite' => 'Lax',
                    
                ]
            );
            // Anti-CTRF token creation
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            
            // Force XSS browser protection if present
            header("X-XSS-Protection: 1; mode=block");
            // Content-Security Policy
            header("Content-Security-Policy: default-src 'self' https://cdnjs.cloudflare.com  https://fonts.gstatic.com; style-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com https://fonts.googleapis.com https://www.w3schools.com; script-src 'self' https://apis.google.com  ; media-src 'self' https://favicon.ico; frame-ancestors 'none'");

        }
    }

    // Getter per la pagina corrente
    public function getCurrentPage()
    {
        return $this->current_page;
    }
}