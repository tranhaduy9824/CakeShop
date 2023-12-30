<?php 
    class ConnectMySql {
        private $server = "localhost";
        private $user = "root";
        private $pass = "";
        private $db = "cuoikiweb";
        private $conn;

        public function __construct() {
            try {
                $this->conn = new PDO("mysql:host=$this->server;dbname=$this->db", $this->user, $this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Lỗi " . $e->getMessage();
            }
        }

        public function getConnection() {
            return $this->conn;
        }
    }
?>