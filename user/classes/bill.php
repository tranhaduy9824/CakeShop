<?php 
    require_once 'connectMySql.php';

    class Bill {
        private $conn;

        public function __construct() {
            $connent = new connectMySql();
            $this->conn = $connent->getConnection();
        }

        public function setBill($userid, $fullname, $phone, $email, $address, $typepay, $totalbill, $sanphams, $status, $note, $delivery) {
            $sql="INSERT INTO bill (userid, fullname, phone, email, address, typepay, totalbill, sanphams, status, note, delivery) VALUES (:userid, :fullname, :phone, :email, :address, :typepay, :totalbill, :sanphams, :status, :note, :delivery)";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':typepay', $typepay);
            $stmt->bindParam(':totalbill', $totalbill);
            $stmt->bindParam(':sanphams', $sanphams);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':note', $note);
            $stmt->bindParam(':delivery', $delivery);
            $stmt->execute();
        }

        public function getBillByIdBill($idbill) {
            $sql="SELECT * FROM bill WHERE idbill=:idbill";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idbill', $idbill);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getBillByUserId($userid) {
            $sql = "SELECT * FROM bill WHERE userid = :userid ORDER BY time DESC LIMIT 1";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>