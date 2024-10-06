<?php
require '../vendor/autoload.php';
use Dotenv\Dotenv;

class Config {
    private static $instance = null;
    private $config;

    private function __construct() {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->config = [
            'DB_HOST' => $_ENV['DB_HOST'],
            'DB_USER' => $_ENV['DB_USER'],
            'DB_PASSWORD' => $_ENV['DB_PASSWORD'],
            'DB_NAME' => $_ENV['DB_NAME'],
            //'MAIL_HOST' => $_ENV['MAIL_HOST'],
            //'MAIL_PWD' => $_ENV['MAIL_PWD'],
            //'MAIL_PORT' => $_ENV['MAIL_PORT'],
            //'MAIL_FROM' => $_ENV['MAIL_FROM'],
            //'MAIL_FROM_NAME' => $_ENV['MAIL_FROM_NAME'],
            //'MAIL_REPLY_TO' => $_ENV['MAIL_REPLY_TO'],
            //'MAIL_REPLY_TO_NAME' => $_ENV['MAIL_REPLY_TO_NAME'],
            //'MAIL_SUBJECT' => $_ENV['MAIL_SUBJECT'],
            //'MAIL_BODY' => $_ENV['MAIL_BODY'],
            //'MAIL_ALT_BODY' => $_ENV['MAIL_ALT_BODY'],
            //'MAIL_TO' => $_ENV['MAIL_TO'],
            //'MAIL_TO_NAME' => $_ENV['MAIL_TO_NAME'],  
        ];

        //TODO: da problemi con db_pwd che è vuoto
        // Validazione delle variabili di configurazione
        //foreach ($this->config as $key => $value) {
        //    if (empty($value)) {
        //        throw new Exception("La variabile di configurazione {$key} non è impostata.");
        //    }
        //}
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Config();
        }

        return self::$instance;
    }

    public function get($key) {
        return $this->config[$key] ?? null;
    }
}