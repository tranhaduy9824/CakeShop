<?php 
    require_once 'connectMySql.php';

    class Carts {
        private $conn;

        public function __construct() {
            $connent = new connectMySql();
            $this->conn = $connent->getConnection();
        }

        public function setCart($userid, $idsp, $fullname, $imagesp, $namesp, $count, $total) {
            $sql="INSERT INTO carts (userid, idsp, fullname, imagesp, namesp, number, total) VALUES (:userid, :idsp, :fullname, :imagesp, :namesp, :number, :total)";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':idsp', $idsp);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':imagesp', $imagesp);
            $stmt->bindParam(':namesp', $namesp);
            $stmt->bindParam(':number', $count);
            $stmt->bindParam(':total', $total);
            $stmt->execute();
        }

        public function getCartByUserId($userid) {
            $sql="SELECT * FROM carts WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function deleteCartById($idcart) {
            $sql="DELETE FROM carts WHERE idcart=:idcart";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idcart', $idcart);
            $stmt->execute();
        }

        public function deleteCartsByUserId($userid) {
            $sql="DELETE FROM carts WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
        }
    }
?>