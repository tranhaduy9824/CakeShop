<?php 
    function getLatestMessage() {
        $server = "localhost";
        $user = "root";
        $pass = "";
        $db = "dacs2";
        try {
          $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
          echo "Lỗi: " . $e->getMessage();
        }
      
        if (isset($_GET["userid"])) {
          $userid = $_GET["userid"];
        }

        $roleSender = "user";
      
        $sql = "SELECT * FROM messages WHERE senderid=:senderid and roleSender=:roleSender ORDER BY messageid DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':senderid', $userid);
        $stmt->bindParam(':roleSender', $roleSender);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
        if ($result) {
          echo json_encode(["success" => true, "message" => $result["content"], "messageId" => $result["messageid"]]);
        } else {
          echo json_encode(["success" => true, "message" => ""]);
        }
    }
      
    getLatestMessage();
?>