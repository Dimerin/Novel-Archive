<?php
require_once __DIR__.'/../config/config.php';

class dbManager {
    private $host;
    private $user;
    private $password;
    private $dbname;
    private $conn;

    private function _initVar(){
        $config = Config::getInstance();
        $this->host = $config->get('DB_HOST');
        $this->user = $config->get('DB_USER');
        $this->password = $config->get('DB_PASSWORD');
        $this->dbname = $config->get('DB_NAME');
    }

    public function __construct() {
        
        $this->_initVar();

        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
            $this->conn = null;
        }
    }
}