<?php 
    require_once 'connectMySql.php';

    class Admins {
        private $conn;

        public function __construct() {
            $connect = new ConnectMySql();
            $this->conn = $connect->getConnection();
        }

        public function getAdminById($adminid) {
            $sql="SELECT * FROM admins WHERE adminid=:adminid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':adminid', $adminid);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getStaffs() {
            $sql = "SELECT * FROM admins WHERE role=:role";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':role', 'staff');
            $stmt->execute();
            return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function handleLogin($adminname, $password) {
            $sql="SELECT * FROM admins WHERE adminname=:adminname and password=:password";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':adminname', $adminname);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function changeInfo($fullname, $email, $phone, $adminid) {
            $sql = "UPDATE admins SET fullname=:fullname, email=:email, phone=:phone WHERE adminid=:adminid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':adminid', $adminid);
            $stmt->execute();
        }

        public function changeAvt($adminid, $avt) {
            $sql = "UPDATE admins SET avt=:avt WHERE adminid=:adminid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':adminid', $adminid);
            $stmt->bindParam(':avt', $avt);
            $stmt->execute();
        }

        public function changePassWord($password, $adminid) {
            $sql = "UPDATE admins SET password=:password WHERE adminid=:adminid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':adminid', $adminid);
            $stmt->execute();
        }

        public function changeInfoStaff($adminid, $fullname, $adminname, $password, $email, $phone) {
            $sql= "UPDATE admins SET adminname=:adminname, fullname=:fullname, email=:email, password=:password, phone=:phone WHERE adminid=:adminid";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':adminid', $adminid);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':adminname', $adminname);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
        }

        public function setStaff($fullname, $adminname, $password, $email, $phone, $role) {
            $sql = "INSERT INTO admins (adminname, fullname, email, password, phone, role) VALUES (:adminname, :fullname, :email, :password, :phone, :role)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':adminname', $adminname);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
        }

        public function deleteStaff($adminid) {
            $sql="DELETE FROM admins WHERE adminid=:adminid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':adminid', $adminid);
            $stmt->execute();
        }
    }
?>