<?php 
    function getLastMessageId() {
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

        echo json_encode(["success" => true, "lastMessageId" => $result["messageid"]]);
    }

    getLastMessageId();
?>