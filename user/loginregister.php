<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/loginregister.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/menu.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/footer.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head>
<body>
    <?php 
        $server="localhost";
        $user="root";
        $pass="";
        $db="dacs2";

        try {
            $conn=new PDO("mysql:host=$server;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                        $sql="SELECT * FROM users WHERE username=:username or email=:email or phone=:phone";
                        $stmt=$conn->prepare($sql);
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':phone', $phone);
                        $stmt->execute();
                        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($result)==0) {
                            $sql="INSERT INTO users (username, fullname, email, password, phone, status) VALUES (:username, :fullname, :email, :password, :phone, :status)";
                            $stmt=$conn->prepare($sql);
                            $stmt->bindParam(':username', $username);
                            $stmt->bindParam(':fullname', $fullname);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':password', $password);
                            $stmt->bindParam(':phone', $phone);
                            $stmt->bindParam(':status', $status);
                            $stmt->execute();
                            echo '<script>alert("Đăng ký thành công")</script>';
                        } else if (count($result)>0){
                            if ($result[0]["username"]==$username) {
                                echo '<script>alert("Tên đăng nhập đã tồn tại")</script>';
                            } else if ($result[0]["email"]==$email) {
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

                $sql="SELECT * FROM users WHERE username=:username and password=:password";
                $stmt=$conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->execute();
                $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result)>0) {
                    echo '<script>alert("Đăng nhập thành công")</script>';
                    $userid=$result[0]["userid"];
                    $username=$result[0]["username"];
                    $fullname=$result[0]["fullname"];
                    $email=$result[0]["email"];
                    $password=$result[0]["password"];
                    $phone=$result[0]["phone"];
                    $status=$result[0]["status"];
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
        } catch (PDOException $e) {
            echo "Lỗi: " .$e->getMessage();
        }

        $sql="SELECT * FROM carts WHERE userid=:userid";
        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $opendetailcart=0;
        
        if (count($result)>0) {
            $opendetailcart=count($result);
        }
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
                        <div class="forgotpass"><a href="/ĐACS2_NEW/user/handle/forgotpass.php">Quên mật khẩu?</a></div>
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
            window.location.href="/ĐACS2_NEW/user/handle/info.php"
        });
        };
        for (const btnpay of btnpays) {
        btnpay.addEventListener('click', function() {
            window.location.href="/ĐACS2_NEW/user/handle/info.php?numberpay=change";
        });
        };
    </script>
</body>
</html>