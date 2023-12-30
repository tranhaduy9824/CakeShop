<?php 
    function getLatestMessage() {
        require_once '../../classes/connectMySql.php';
        require_once '../../classes/admins.php';
        require_once '../../classes/users.php';
        require_once '../../classes/bill.php';
        require_once '../../classes/messages.php';
        require_once '../../classes/sanphams.php';

        $messages = new Messages();
      
        if (isset($_GET["userid"])) {
          $userid = $_GET["userid"];
        }

        $roleSender = "user";
      
        $result = $messages->getLastestMessage($userid, $roleSender);
      
        if ($result) {
          echo json_encode(["success" => true, "message" => $result["content"], "messageId" => $result["messageid"]]);
        } else {
          echo json_encode(["success" => true, "message" => ""]);
        }
    }
      
    getLatestMessage();
?>