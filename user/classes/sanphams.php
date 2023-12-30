<?php 
    require_once 'connectMySql.php';

    class Sanphams {
        private $conn;

        public function __construct() {
            $connent = new connectMySql();
            $this->conn = $connent->getConnection();
        }

        public function getSanphams() {
            $sql="SELECT * FROM sanphams";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getSanphamsById($idsp) {
            $sql="SELECT * FROM sanphams WHERE idsp=:idsp";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idsp', $idsp);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }
 
        public function getSanphamByRibbon($ribbon) {
            $sql="SELECT * FROM sanphams WHERE ribbon=:ribbon";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':ribbon', $ribbon);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function searchSanPham($start, $limit) {
            if (!empty($_GET["search"])) {
                $search=$_GET["search"];
                $sql="SELECT * FROM sanphams WHERE namesp LIKE :search LIMIT :start, :limit";
                $stmt=$this->conn->prepare($sql);
                $stmt->bindValue(':search', '%' .$search. '%');
            } else if (isset($_GET["type"])) {
                $type=$_GET["type"];
                $sql="SELECT * FROM sanphams WHERE type=:type LIMIT :start, :limit";
                $stmt=$this->conn->prepare($sql);
                $stmt->bindParam(':type', $type);
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