<?php 
    require_once 'connectMySql.php';

    class Comments {
        private $conn;

        public function __construct() {
            $connent = new connectMySql();
            $this->conn = $connent->getConnection();
        }

        public function setComment($userid, $content) {
            $sql="INSERT INTO comments (userid, content) VALUES (:userid, :content)";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':content', $content);
            $stmt->execute();
        }

        public function getComments() {
            $sql="SELECT * FROM comments ORDER BY created_at DESC";
            $stmt=$this->conn->prepare($sql);
            $stmt->execute();
            return $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function deleteCommentbyId($idcomment) {
            $sql="DELETE FROM comments WHERE idcomment=:idcomment";
            $stmt=$this->conn->prepare($sql);
            $stmt->bindParam(':idcomment', $idcomment);
            $stmt->execute();
        }
    }
?>