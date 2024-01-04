<?php 
    require_once 'connectMySql.php';

    class Messages {
        private $conn;

        public function __construct() {
            $connent = new connectMySql();
            $this->conn = $connent->getConnection();
        }

        public function getMessages($userid) {
            $sql="SELECT * FROM messages WHERE senderid=:senderid or receiverid=:receiverid ";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':senderid', $userid);
            $stmt->bindParam(':receiverid', $userid);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);  
        }
    }
?>