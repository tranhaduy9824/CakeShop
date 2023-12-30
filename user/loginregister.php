<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/loginregister.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/menu.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/footer.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head>
<body>
    <?php 
        require_once 'classes/connectMySql.php';
        require_once 'classes/users.php';
        require_once 'classes/carts.php';
        require_once 'classes/comments.php';
        require_once 'classes/sanphams.php';
        require_once 'classes/bill.php';
        require_once 'classes/messages.php';

        $connect = new ConnectMySql();
        $conn = $connect->getConnection();

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['register'])) {
            $username=$_POST['username'];
            $fullname=$_POST['fullname'];
            $email=$_POST['email'];
            $password=$_POST['password'];
            $confirm=$_POST['confirm'];
            $phone=$_POST['phone'];
            $status=0;

            if ($password===$confirm) {
                if (preg_match("/^\d{10}$/", $phone)) {
                    $users = new Users();
                    $checkUser = $users->checkUser($username, $email, $phone);
                    if (count($checkUser)==0) {
                        $users->addUser($username, $fullname, $email, $password, $phone, $status);
                        echo '<script>alert("Đăng ký thành công")</script>';
                    } else if (count($checkUser)>0){
                        if ($checkUser[0]["username"]==$username) {
                            echo '<script>alert("Tên đăng nhập đã tồn tại")</script>';
                        } else if ($checkUser[0]["email"]==$email) {
                            echo '<script>alert("Email đã tồn tại")</script>';
                        }
                        else {
                            echo '<script>alert("Số điện thoại đã tồn tại")</script>';
                        }
                    }
                } else {    
                    echo '<script>alert("Số điện thoại không chính xác")</script>';
                }
            }
            else {
                echo '<script>alert("Mật khẩu không trùng nhau")</script>';
            }
        }

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['login'])) {
            $username=$_POST["username"];
            $password=$_POST["password"];

            $users = new Users();
            $checkLogin = $users->checkLogin($username, $password);
            if ($checkLogin) {
                echo '<script>alert("Đăng nhập thành công")</script>';
                $userid=$checkLogin["userid"];
                $username=$checkLogin["username"];
                $fullname=$checkLogin["fullname"];
                $email=$checkLogin["email"];
                $password=$checkLogin["password"];
                $phone=$checkLogin["phone"];
                $status=$checkLogin["status"];
                setcookie('userid', $userid, time() + 86400, '/');
                setcookie('username', $username, time() + 86400, '/');
                setcookie('fullname', $fullname, time() + 86400, '/');
                setcookie('email', $email, time() + 86400, '/');
                setcookie('password', $password, time() + 86400, '/');
                setcookie('phone', $phone, time() + 86400, '/');
                setcookie('status', $status, time() + 86400, '/');

                header("Location: index.php");
                exit();
            }
            else {
                echo '<script>alert("Đăng nhập thất bại")</script>';
            }
        }

        if (isset($userid)) {
            $userid = $userid;
            $carts = new Carts();
            $listCart = $carts->getCartByUserId($userid);
        }
        $opendetailcart=0;
        
        // if (count($listCart)>0) {
        //     $opendetailcart=count($result);
        // }
    ?>

    <!-- Menu -->
    <?php include 'menu.php'; ?>

    <!-- Main -->
    <div id="main">
        <div id="btnchange">
            <div class="btnlogin change">
                Đăng nhập
            </div>
            <div class="btnregister">
                Đăng ký
            </div>
        </div>
        <div id="login-register">
            <!-- Đăng nhập -->
            <div id="login">
                <div class="box-login">
                    <form action="loginregister.php" method="post">
                        <label for="">Tên đăng nhập</label>
                        <br>
                        <input type="text" name="username" required placeholder="Nhập tên đăng nhập">
                        <br>
                        <label for="">Mật khẩu</label>
                        <br>
                        <input type="password" name="password" required placeholder="*********">
                        <br>
                        <button type="submit" name="login">Đăng nhập</button>
                        <br>
                        <div class="or">OR</div>
                        <div class="google-facebook">
                            <div class="google">
                                <i class="fab fa-google"></i>
                                Google
                            </div>
                            <div class="facebook">
                                <i class="fab fa-facebook-f"></i>
                                Facebook
                            </div>
                        </div>
                        <div class="forgotpass"><a href="/CuoiKiWeb/user/handle/forgotpass.php">Quên mật khẩu?</a></div>
                    </form>
                </div>
            </div>

            <!-- Đăng ký -->
            <div id="register">
                <div class="box-register">
                    <form action="loginregister.php" method="post">
                        <label for="">Tên đăng nhập</label>
                        <br>
                        <input type="text" name="username" required placeholder="Nhập tên đăng nhập">
                        <br>
                        <label for="">Họ và tên</label>
                        <br>
                        <input type="text" name="fullname" required placeholder="Họ và tên đầy đủ">
                        <br>
                        <label for="">Email</label>
                        <br>
                        <input type="email" name="email" required placeholder="*****@gmail.com">
                        <br>
                        <label for="">Mật khẩu</label>
                        <br>
                        <input type="password" id="password" name="password" onkeyup="ktpass()" required placeholder="********">
                        <br>
                        <label for="">Nhập lại mật khẩu</label>
                        <br>
                        <input type="password" name="confirm" required placeholder="********">
                        <br>
                        <label for="">Số điện thoại</label>
                        <br>
                        <input type="text" name="phone" required placeholder="***************">
                        <br>
                        <button type="submit" name="register">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart -->
    <?php include 'cart.php';?>

    <!-- Footer -->
    <?php include 'footer.php'?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function ktpass() {
            var password = document.getElementById("password").value;

            if (password.length >= 8) {
                document.getElementById("message").innerHTML = "Mật khẩu hợp lệ.";
            } else {
                document.getElementById("message").innerHTML = "Mật khẩu phải có ít nhất 8 kí tự.";
            }
        }
        
        var btnlogin = document.querySelector('.btnlogin');
        var btnregister = document.querySelector('.btnregister');
        var boxlogin = document.querySelector('.box-login');
        var boxregister = document.querySelector('.box-register');

        btnlogin.addEventListener('click', function() {
            boxlogin.classList.remove('open');
        });
        btnlogin.addEventListener('click', function() {
            boxregister.classList.remove('open');
        });
        btnlogin.addEventListener('click', function() {
            btnlogin.classList.add('change');
        });
        btnlogin.addEventListener('click', function() {
            btnregister.classList.remove('change');
        });
        btnregister.addEventListener('click', function() {
            boxregister.classList.add('open');
        });
        btnregister.addEventListener('click', function() {
            boxlogin.classList.add('open');
        });
        btnregister.addEventListener('click', function() {
            btnregister.classList.add('change');
        });
        btnregister.addEventListener('click', function() {
            btnlogin.classList.remove('change');
        });
        
        var btncart=document.querySelector('.cart');
        var boxcart=document.querySelector('.box-cart');

        btncart.addEventListener('click', function() {
            if (boxcart.classList.contains('open')) {
                boxcart.classList.remove('open');
            } else {
                boxcart.classList.add('open');
            }
        });

        var btndetails=document.querySelectorAll('.btn-detail');
        var btnpays=document.querySelectorAll('.btn-pay');
        for (const btndetail of btndetails) {
        btndetail.addEventListener('click', function() {
            window.location.href="/CuoiKiWeb/user/handle/info.php"
        });
        };
        for (const btnpay of btnpays) {
        btnpay.addEventListener('click', function() {
            window.location.href="/CuoiKiWeb/user/handle/info.php?numberpay=change";
        });
        };
    </script>
</body>
</html>