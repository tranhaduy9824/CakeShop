<?php
    require_once '../classes/connectMySql.php';
    require_once '../classes/users.php';
    require_once '../classes/carts.php';
    require_once '../classes/comments.php';
    require_once '../classes/sanphams.php';
    require_once '../classes/bill.php';
    require_once '../classes/messages.php';

    function sendMessage() {
        $connect = new ConnectMySql();
        $conn = $connect->getConnection();

        $userid = (isset($_COOKIE["userid"])) ? $_COOKIE["userid"] : null; 
        $content = $_POST["content"];
        $adminid = 1;
        $roleSender = "user";

        $sql = "INSERT INTO messages (senderid, roleSender, receiverid, content) VALUES (:senderid, :roleSender, :receiverid, :content)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':senderid', $userid);
        $stmt->bindParam(':roleSender', $roleSender);
        $stmt->bindParam(':receiverid', $adminid);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        echo json_encode(["success" => true, "message" => $content]); 
    }

    sendMessage();
?>