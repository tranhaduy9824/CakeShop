<?php
    function sendMessage() {
        $server = "localhost";
        $user = "root";
        $pass = "";
        $db = "dacs2";
        try {
            $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            exit; 
        }

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