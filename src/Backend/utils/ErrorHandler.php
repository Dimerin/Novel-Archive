<?php

class InvalidRequestException extends Exception {}
class UserNotFoundException extends Exception {}
class InvalidTokenException extends Exception {}
class RegistrationException extends Exception {}
class FailedTokenDeletionException extends Exception {}
class UserInactiveException extends Exception {}
class PasswordMismatchException extends Exception {}
class DatabaseException extends Exception {}
class TooManyLoginAttemptsException extends Exception {}

class ErrorHandler {
    public function __construct() {
        // Imposta i gestori personalizzati
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // Gestore degli errori
    public function handleError($severity, $message, $file, $line) {
        // Trasforma un errore in eccezione se è fatale
        if (!(error_reporting() & $severity)) {
            // Questo errore non dovrebbe essere gestito
            return;
        }
        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    // Gestore delle eccezioni
    public function handleException($exception) {
        // Log dell'eccezione
        //$this->logError($exception->getMessage(), $exception->getFile(), $exception->getLine());

        switch (get_class($exception)) {
            case InvalidRequestException::class:
                $this->sendResponse(['status' => 'error', 'message' => $exception->getMessage()], 400);
                break;
            
            case UserNotFoundException::class:
                $this->sendResponse(['status' => 'error', 'message' => $exception->getMessage()], 409);
                break;
            
            case InvalidTokenException::class:
                $this->sendResponse(['status' => 'error', 'message' => 'Invalid Token'], 500);
                break;  
            
            case RegistrationException::class:
                $this->sendResponse(['status' => 'error', 'message' => 'Registration failed.'], 500);
                break;
            
            case DatabaseException::class:
                $this->sendResponse(['status' => 'error', 'message' => $exception->getMessage()], 500);
                break;
            
            case FailedTokenDeletionException::class:
                $this->sendResponse(['status' => 'error', 'message' => $exception->getMessage()], 500);
                break;
            
            case PasswordMismatchException::class:
                $this->sendResponse(['status' => 'error', 'message' => $exception->getMessage()], 400);
                break;
            
            case UserInactiveException::class:
                $this->sendResponse(['status' => 'error', 'message' => $exception->getMessage()], 401);
                break;

            default:
            return $this->sendResponse(['status' => 'error', 'message' => $exception->getMessage()], 500);
                break;
        }
    }

    // Gestore degli errori fatali (eseguito al termine dello script)
    public function handleShutdown() {
        $error = error_get_last();
        if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
            // Gestione dell'errore fatale
            #$this->logError($error['message'], $error['file'], $error['line']);
            http_response_code(500);
            echo "Si è verificato un errore critico. Contattare l'amministratore.";
        }
    }

    // Funzione per registrare gli errori su file di log
    /*
    private function logError($message, $file, $line) {
        $logMessage = "[" . date("Y-m-d H:i:s") . "] Errore in $file alla linea $line: $message\n";
        error_log($logMessage, 3, __DIR__ . '/error_log.txt'); // Salva in un file di log
    }*/
}
?>
