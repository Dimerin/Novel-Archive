<?php

class dbManager {
    private $host;
    private $user;
    private $password;
    private $dbname;
    private $conn;

    private function _initVar(){
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USER');
        $this->password = getenv('DB_PASSWORD');
        $this->dbname = getenv('DB_NAME');
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