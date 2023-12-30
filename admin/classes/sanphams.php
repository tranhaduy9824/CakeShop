<?php 
    require_once 'connectMySql.php';

    class Sanphams {
        private $conn;

        public function __construct() {
            $connect = new ConnectMySql();
            $this->conn = $connect->getConnection();
        }

        public function setSanpham($idsp, $imagesp, $namesp, $dessp, $price, $ribbon, $type) {
            $sql="INSERT INTO sanphams (idsp, imagesp, namesp, dessp, price, ribbon, type) VALUES (:idsp, :imagesp, :namesp, :dessp, :price, :ribbon, :type)";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idsp', $idsp);
            $stmt->bindParam(':imagesp', $imagesp);
            $stmt->bindParam(':namesp', $namesp);
            $stmt->bindParam(':dessp', $dessp);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':ribbon', $ribbon);
            $stmt->bindParam(':type', $type);
            $stmt->execute();
        }

        public function getSanphams() {
            $sql="SELECT * FROM sanphams";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getSanphamById($idsp) {
            $sql="SELECT * FROM sanphams WHERE idsp=:idsp";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idsp', $idsp);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function deleteSanpham($idsp) {
            $sql="DELETE FROM sanphams WHERE idsp=:idsp";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idsp', $idsp);
            $stmt->execute();
        }

        public function changeSanpham($idsp, $namesp, $dessp, $price, $ribbon, $type) {
            if (!empty($_FILES['imagesp']['tmp_name'])) {
                $imagesp=file_get_contents($_FILES['imagesp']['tmp_name']);
                $sql="UPDATE sanphams SET imagesp=:imagesp, namesp=:namesp, dessp=:dessp, price=:price, ribbon=:ribbon, type=:type WHERE idsp=:idsp";
                $stmt=$this->conn->prepare($sql);
                $stmt->bindParam(':imagesp', $imagesp);
            } else {
                $sql="UPDATE sanphams SET namesp=:namesp, dessp=:dessp, price=:price, ribbon=:ribbon, type=:type WHERE idsp=:idsp";
                $stmt=$this->conn->prepare($sql);
            }
            $stmt->bindParam(':idsp', $idsp);
            $stmt->bindParam(':namesp', $namesp);
            $stmt->bindParam(':dessp', $dessp);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':ribbon', $ribbon);
            $stmt->bindParam(':type', $type);
            $stmt->execute();
        }

        public function searchSanphams($start, $limit) {
            if (isset($_GET["search"]) && !empty($_GET["search"])) {
                $search = $_GET['search'];
                $sql="SELECT * FROM sanphams WHERE namesp LIKE :search LIMIT :start, :limit";
                $stmt=$this->conn->prepare($sql);
                $stmt->bindValue(':search', '%' . $search . '%');
            } else {
                $sql="SELECT * FROM sanphams LIMIT :start, :limit";
                $stmt=$this->conn->prepare($sql);
            }
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>