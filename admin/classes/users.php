<?php 
    require_once 'connectMySql.php';

    class Users {
        private $conn;

        public function __construct() {
            $connect = new ConnectMySql();
            $this->conn = $connect->getConnection();
        }

        public function getUsers() {
            $sql="SELECT * FROM users";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getUserById($userid) {
            $sql="SELECT * FROM users WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function get4UserNew() {
            $sql="SELECT * FROM users ORDER BY userid DESC LIMIT 4";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function deleteUserByUserId($userid) {
            $sql1 = "DELETE FROM carts WHERE userid=:userid";
            $sql2 = "DELETE FROM comments WHERE userid=:userid";
            $sql3 = "DELETE FROM messages WHERE senderid=:senderid OR receiverid=:receiverid";
            $sql4 = "DELETE FROM users WHERE userid=:userid";
                
            $stmt1 = $this->conn->prepare($sql1);
            $stmt2 = $this->conn->prepare($sql2);
            $stmt3 = $this->conn->prepare($sql3);
            $stmt4 = $this->conn->prepare($sql4);
            $stmt1->bindParam(':userid', $userid);
            $stmt2->bindParam(':userid', $userid);
            $stmt3->bindParam(':senderid', $userid);
            $stmt3->bindParam(':receiverid', $userid);
            $stmt4->bindParam(':userid', $userid);
            $stmt1->execute();
            $stmt2->execute();
            $stmt3->execute();
            $stmt4->execute();
        }

        public function handleBlock($status, $userid) {
            $sql="UPDATE users SET status=:status WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam('userid', $userid);
            $stmt->execute();
        }

        public function changeInfoUser($userid, $username, $fullname, $password, $email, $phone) {
            $sql="UPDATE users SET username=:username, fullname=:fullname, email=:email, password=:password, phone=:phone WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
        }

        public function searchUser($start, $limit) {
            if (!empty($_GET["search"])) {
                $search=$_GET["search"];
                $sql="SELECT * FROM users WHERE fullname LIKE :search LIMIT :start, :limit";
                $stmt=$this->conn->prepare($sql);
                $stmt->bindValue(':search', '%' .$search. '%');
            } else {
                $sql="SELECT * FROM users LIMIT :start, :limit";
                $stmt=$this->conn->prepare($sql);
            }
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>