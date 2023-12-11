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

        $adminid = (isset($_COOKIE["adminid"])) ? $_COOKIE["adminid"] : null; 
        $content = $_POST["content"];
        $userid = $_POST["userid"];
        $roleSender = "admin";

        $sql = "INSERT INTO messages (senderid, roleSender, receiverid, content) VALUES (:senderid, :roleSender, :receiverid, :content)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':senderid', $adminid);
        $stmt->bindParam(':roleSender', $roleSender);
        $stmt->bindParam(':receiverid', $userid);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        echo json_encode(["success" => true, "message" => $content]); 
    }

    sendMessage();
?>