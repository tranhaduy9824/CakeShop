<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/updatepass.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/menu.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head>
<body>
    <?php
        require_once '../classes/connectMySql.php';
        require_once '../classes/users.php';
        require_once '../classes/carts.php';
        require_once '../classes/comments.php';
        require_once '../classes/sanphams.php';
        require_once '../classes/bill.php';
        require_once '../classes/messages.php';

        $userid="";

        if (isset($_GET["userid"])) {
            $userid=$_GET["userid"];
        }

        if (isset($_COOKIE["userid"])) {
            $userid=$_COOKIE["userid"];
        }

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["update-pass"])) {
            $userid=$_POST["userid"];
            $password=$_POST["password"];
            $confirm=$_POST["confirm"];

            if (empty($userid)) {
                echo '<script>alert("Không tìm thấy người dùng")</script>';
            } else if ($password===$confirm) {
                $users = new Users();
                $users->changePassWord($userid, $password);
                echo '<script>alert("Cập nhập mật khẩu thành công");window.location.href="/CuoiKiWeb/user/index.php";</script>';
            } else {
                echo '<script>alert("Mật khẩu không trùng nhau")</script>';
            }
        }
    ?>

    <!-- Menu -->
    <?php include '../menu.php'?>

    <!-- Main -->
    <div id="main">
        <div class="box-updatepass">
            <h1>Đổi mật khẩu</h1>

            <form action="updatepass.php" method="post">
                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                <label for="">Mật khẩu mới:</label>
                <input type="password" name="password" required>
                <br> <br>
                <label for="">Xác nhận mật khẩu:</label>
                <input type="password" name="confirm" required>
                <br>
                <input type="submit" name="update-pass" value="Cập nhập"> 
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../footer.php';?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>