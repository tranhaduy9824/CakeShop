<?php
    function sendMessage() {
        require_once '../../classes/connectMySql.php';
        require_once '../../classes/admins.php';
        require_once '../../classes/users.php';
        require_once '../../classes/bill.php';
        require_once '../../classes/messages.php';
        require_once '../../classes/sanphams.php';

        $messages = new Messages();

        $adminid = (isset($_COOKIE["adminid"])) ? $_COOKIE["adminid"] : null; 
        $content = $_POST["content"];
        $userid = $_POST["userid"];
        $roleSender = "admin";

        $messages->setMessage($adminid, $roleSender, $userid, $content);

        echo json_encode(["success" => true, "message" => $content]); 
    }

    sendMessage();
?>