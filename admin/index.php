<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head> 
<body>
    <?php 
        require_once 'classes/connectMySql.php';
        require_once 'classes/admins.php';
        require_once 'classes/users.php';
        require_once 'classes/bill.php';
        require_once 'classes/messages.php';
        require_once 'classes/sanphams.php';

        $admins = new Admins();

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["login-admin"])) {
            $adminname=$_POST["username"];
            $password=$_POST["password"];

            $result = $admins->handleLogin($adminname, $password);

            if ($result) {
                setcookie('adminid', $result["adminid"], time() + 86400, '/');
                setcookie('adminname', $result["adminname"], time() + 86400, '/');
                setcookie('adminfullname', $result["fullname"], time() + 86400, '/');
                setcookie('role', $result["role"], time() + 86400, '/');
                echo '<script>alert("Đăng nhập thành công");window.location.href="page/index.php";</script>';
            } else {
                echo '<script>alert("Tài khoản hoặc mật khẩu không đúng")</script>';
            }
        }
    ?>

    <div id="login">
        <div class="bgr"></div>
        <div class="box-login">
            <div class="login">
                <div class="img-login">
                    <img src="/CuoiKiWeb/admin/img/login.jpg" alt="">
                </div>
                <div class="form-login">
                    <h2>Đăng nhập hệ thống quản lí</h2>
                    <form action="index.php" method="post">
                        <div class="username-admin">
                            <input type="text" name="username" required placeholder="Tài khoản quản trị">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="password-admin">
                            <input type="password" name="password" required placeholder="Mật khẩu">
                            <i class="fas fa-key"></i>
                        </div>
                        <input type="submit" name="login-admin" value="Đăng nhập">
                    </form>
                </div>
            </div>
            <p><b>Phần mềm quản lý bán hàng 2023 bởi <span>Duy</span></b></p>
        </div>
    </div>
</body>
</html>