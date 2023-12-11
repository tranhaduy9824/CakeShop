<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/updatepass.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/menu.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head>
<body>
    <?php
        $server="localhost";
        $user="root";
        $pass="";
        $db="dacs2";

        $userid="";

        if (isset($_GET["userid"])) {
            $userid=$_GET["userid"];
        }

        if (isset($_COOKIE["userid"])) {
            $userid=$_COOKIE["userid"];
        }

        try {
            $conn=new PDO("mysql:host=$server;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Lỗi: " .$e->getMessage();
        }

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["update-pass"])) {
            $userid=$_POST["userid"];
            $password=$_POST["password"];
            $confirm=$_POST["confirm"];

            if (empty($userid)) {
                echo '<script>alert("Không tìm thấy người dùng")</script>';
            } else if ($password===$confirm) {
                $sql="UPDATE users SET password=:password WHERE userid=:userid";
                $stmt=$conn->prepare($sql);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':userid', $userid);
                $stmt->execute();
                echo '<script>alert("Cập nhập mật khẩu thành công");window.location.href="/ĐACS2_NEW/user/index.php";</script>';
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