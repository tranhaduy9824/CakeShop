<?php 
    require_once 'connectMySql.php';

    class Bill {
        private $conn;

        public function __construct() {
            $connect = new ConnectMySql();
            $this->conn = $connect->getConnection();
        }

        public function getBills() {
            $sql="SELECT * FROM bill";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getBillById($idbill) {
            $sql="SELECT * FROM bill WHERE idbill=:idbill";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idbill', $idbill);
            $stmt->execute();
            return $result=$stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function get4BillNew() {
            $sql="SELECT * FROM bill ORDER BY idbill DESC LIMIT 4";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getBillByUserId($userid) {
            $sql="SELECT * FROM bill WHERE userid=:userid";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam('userid', $userid);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function deleteBill($idbill) {
            $sql="DELETE FROM bill WHERE idbill=:idbill";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idbill', $idbill);
            $stmt->execute();
        }

        public function updateBill($idbill, $column) {
            $sql = "UPDATE bill SET ";
            
            if ($column === "delivery") {
                $sql .= "delivery = :delivery ";
            } else {
                $sql .= "status = :status ";
            }
            
            $sql .= "WHERE idbill = :idbill";
            
            $stmt = $this->conn->prepare($sql);
            
            if ($column === "delivery") {
                $stmt->bindValue(':delivery', "Đã giao");
            } else {
                $stmt->bindValue(':status', "Đã thanh toán");
            }
            
            $stmt->bindParam(':idbill', $idbill);
            $stmt->execute();
        }

        public function searchBill($start, $limit, $delivery, $status) {
            if (!empty($_GET["search"])) {
                $search = $_GET["search"];
                
                if (empty($delivery) && empty($status)) {
                    $sql = "SELECT * FROM bill WHERE fullname LIKE :search ORDER BY time DESC LIMIT :start, :limit";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindValue(':search', '%' . $search . '%');
                } else {
                    $sql = "SELECT * FROM bill WHERE fullname LIKE :search";
                    if (!empty($delivery)) {
                        $sql .= " AND delivery = :delivery";
                    }
                    if (!empty($status)) {
                        $sql .= " AND status = :status";
                    }
                    $sql .= " ORDER BY time DESC LIMIT :start, :limit";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindValue(':search', '%' . $search . '%');
                    if (!empty($delivery)) {
                        $stmt->bindParam(':delivery', $delivery);
                    }
                    if (!empty($status)) {
                        $stmt->bindParam(':status', $status);
                    }
                }
            } else {
                if (empty($delivery) && empty($status)) {
                    $sql = "SELECT * FROM bill ORDER BY time DESC LIMIT :start, :limit";
                    $stmt = $this->conn->prepare($sql);
                } else {
                    $sql = "SELECT * FROM bill WHERE 1=1";
                    if (!empty($delivery)) {
                        $sql .= " AND delivery = :delivery";
                    }
                    if (!empty($status)) {
                        $sql .= " AND status = :status";
                    }
                    $sql .= " ORDER BY time DESC LIMIT :start, :limit";
                    $stmt = $this->conn->prepare($sql);
                    if (!empty($delivery)) {
                        $stmt->bindParam(':delivery', $delivery);
                    }
                    if (!empty($status)) {
                        $stmt->bindParam(':status', $status);
                    }
                }
            }
            
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>