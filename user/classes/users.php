<?php 
    require_once 'connectMySql.php';

    class Users {
        private $conn;

        public function __construct() {
            $connent = new connectMySql();
            $this->conn = $connent->getConnection();
        }

        public function getListUsers() {
            $sql = 'SELECT * FROM users';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getUserByUserId($userid) {
            $sql="SELECT * FROM users WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function checkUser($username, $email, $phone) {
            $sql="SELECT * FROM users WHERE username=:username or email=:email or phone=:phone";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function addUser($username, $fullname, $email, $password, $phone, $status) {
            $sql="INSERT INTO users (username, fullname, email, password, phone, status) VALUES (:username, :fullname, :email, :password, :phone, :status)";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
        }

        public function checkLogin($username, $password) {
            $sql="SELECT * FROM users WHERE username=:username and password=:password";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function changePassWord($userid, $password) {
            $sql="UPDATE users SET password=:password WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
        }

        public function changeAvt($userid, $avt) {
            $sql = "UPDATE users SET avt=:avt WHERE userid=:userid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':avt', $avt);
            $stmt->execute();
        }

        public function changeInfo($userid, $email1, $phone1) {
            $sql="UPDATE users SET email=:email, phone=:phone WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':email', $email1);
            $stmt->bindParam(':phone', $phone1);
            $stmt->execute();
        }

        public function getUserByEmail($email) {
            $sql="SELECT * FROM users WHERE email=:email";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>