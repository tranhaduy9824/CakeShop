<?php 
    if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["search"])) {
        $search=$_POST["search"];

        header("Location: /ĐACS2_NEW/user/pages/Sản phẩm/sanphamphan.php?search=$search");
        exit();
    }
?>