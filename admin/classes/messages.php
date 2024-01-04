<?php 
    require_once 'connectMySql.php';

    class Messages {
        private $conn;

        public function __construct() {
            $connect = new ConnectMySql();
            $this->conn = $connect->getConnection();
        }

        public function setMessage($adminid, $roleSender, $userid, $content) {
            $sql = "INSERT INTO messages (senderid, roleSender, receiverid, content) VALUES (:senderid, :roleSender, :receiverid, :content)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':senderid', $adminid);
            $stmt->bindParam(':roleSender', $roleSender);
            $stmt->bindParam(':receiverid', $userid);
            $stmt->bindParam(':content', $content);
            $stmt->execute();
        }

        public function getLastestMessage($userid, $roleSender) {
            $sql = "SELECT * FROM messages WHERE senderid=:senderid and roleSender=:roleSender ORDER BY messageid DESC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':senderid', $userid);
            $stmt->bindParam(':roleSender', $roleSender);
            $stmt->execute();
            return $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getMessages($userid) {
            $sql="SELECT * FROM messages WHERE senderid=:senderid or receiverid=:receiverid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':senderid', $userid);
            $stmt->bindParam(':receiverid', $userid);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getMessagesBySenderId($userid) {
            $sql="SELECT * FROM messages WHERE senderid=:senderid ORDER BY senderid DESC";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindValue(':senderid', $userid);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getMessagesByRoleSender() {
            $sql="SELECT * FROM messages WHERE roleSender=:roleSender ORDER BY senderid DESC";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindValue(':roleSender', "user");
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getLastMessage($userid) {
            $sql = "SELECT * FROM messages WHERE senderid=:senderid or receiverid=:receiverid ORDER BY timestamp DESC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':senderid', $userid);
            $stmt->bindParam(':receiverid', $userid);
            $stmt->execute();
            return $lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function searchUsers($search) {
            $sql="SELECT * FROM users WHERE fullname LIKE :fullname";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindValue(':fullname', '%' . $search . '%');
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>