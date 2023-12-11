<?php 
    function getMessage() {
        $server="localhost";
        $user="root";
        $pass="";
        $db="dacs2";
        try {
            $conn=new PDO("mysql:host=$server;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Lỗi: " .$e->getMessage();
        }

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