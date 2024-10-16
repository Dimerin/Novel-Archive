<?php

class Logger {
    private static $instance = null; // Unica istanza del logger
    private $logFile;
    private $sensitiveFields = ['password'];//, 'csfr_token'];

    // Costruttore privato per prevenire l'uso diretto di "new"
    private function __construct($filePath) {
        $this->logFile = $filePath;
    }

    // FIXME: cambia il percorso del file di log e i permessi di scrittura
    // Singleton: ottiene l'unica istanza del logger
    public static function getInstance($filePath = "/../logs/app_log.txt") {
        if (self::$instance === null) {
            self::$instance = new self(__DIR__ . $filePath);
        }
        return self::$instance;
    }

    // Funzione principale per loggare i dati
    private function logRequest($action, $responseCode, $message = '', $level = 'INFO') {
        // Rileva i dati della richiesta in base al metodo
        $requestData = $this->getRequestData();

        // Filtra i campi sensibili come password
        $filteredRequestData = $this->filterSensitiveData($requestData);

        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'log_level' => $level, 
            'client_ip' => $this->getClientIp(),
            'action' => $action,
            'method' => $_SERVER['REQUEST_METHOD'],
            'url' => $_SERVER['REQUEST_URI'],
            'query_params' => $_GET, // Se presenti
            'body_params' => $filteredRequestData, // Dati del form o POST
            'response_code' => $responseCode,
            'message' => $message,
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        $this->writeLog($logEntry);
    }

    // Recupera i dati della richiesta in base al metodo
    private function getRequestData() {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                return $_POST;
            case 'GET':
                return $_GET;
            default:
                return [];
        }
    }

    // Filtra i campi sensibili dai dati della richiesta
    private function filterSensitiveData($requestData) {
        if (is_array($requestData)) {
            foreach ($this->sensitiveFields as $field) {
                if (isset($requestData[$field])) {
                    $requestData[$field] = '[FILTERED]'; // Maschera il campo
                }
            }
        }
        return $requestData;
    }

    // Scrivi il log su file
    private function writeLog($logEntry) {
        // FIXME: meglio un CSV
        $logMessage = json_encode($logEntry) . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    // Ottieni l'IP del client
    private function getClientIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    // Funzioni di livello per semplificare l'uso del logger
    public function debug($action, $message = '', $responseCode = 200) {
        $this->logRequest($action, $responseCode, $message, 'DEBUG');
    }

    public function info($action, $message = '', $responseCode = 200) {
        $this->logRequest($action, $responseCode, $message, 'INFO');
    }

    public function warning($action, $message = '', $responseCode = 200) {
        $this->logRequest($action, $responseCode, $message, 'WARNING');
    }

    public function error($action, $message = '', $responseCode = 500) {
        $this->logRequest($action, $responseCode, $message, 'ERROR');
    }
}
