<?php 
    require_once '../classes/connectMySql.php';
    require_once '../classes/users.php';
    require_once '../classes/carts.php';
    require_once '../classes/comments.php';
    require_once '../classes/sanphams.php';
    require_once '../classes/bill.php';
    require_once '../classes/messages.php';
    
    function getMessage() {
        $connect = new ConnectMySql();
        $conn = $connect->getConnection();

        $roleSender = "admin";
        $userid = isset($_COOKIE["userid"]) ? $_COOKIE["userid"] : null;

        $sql = "SELECT * FROM messages WHERE roleSender=:roleSender and receiverid=:receiverid ORDER BY messageid DESC LIMIT 1";
        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':roleSender', $roleSender);
        $stmt->bindParam(':receiverid', $userid);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC); 
        
        if ($result) {
            echo json_encode(["success" => true, "message" => $result["content"], "messageId" => $result["messageid"]]);
        } else {
            echo json_encode(["success" => true, "message" => ""]);
        }
    }

    getMessage();
?>