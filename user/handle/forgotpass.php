<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/forgotpass.css">
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

        session_start();
        require '/CÔNG NGHỆ VÀ LẬP TRÌNH WEB/CuoiKiWeb/PHPMailer-master/src/PHPMailer.php';
        require '/CÔNG NGHỆ VÀ LẬP TRÌNH WEB/CuoiKiWeb/PHPMailer-master/src/SMTP.php';
        require '/CÔNG NGHỆ VÀ LẬP TRÌNH WEB/CuoiKiWeb/PHPMailer-master/src/Exception.php';
        require '/CÔNG NGHỆ VÀ LẬP TRÌNH WEB/CuoiKiWeb/PHPMailer-master/src/POP3.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        function randomCode() {
            $randomcode = mt_rand(100000, 999999);
            return $randomcode;
        }

        function sendCodeEmail($email, $code) {
            $mail=new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;                                 
                $mail->isSMTP();                                      
                $mail->Host = 'smtp.gmail.com';  
                $mail->SMTPAuth = true;                               
                $mail->Username = 'tranhaduy204@gmail.com';                
                $mail->Password = 'iorz aeqf yxyo tnvd';                      
                $mail->SMTPSecure = 'tls';                           
                $mail->Port = 587;                                   
            
                $mail->setFrom('tranhaduy204@gmail.com', 'Cakeshop');
                $mail->addAddress($email);     
            
                $mail->isHTML(true);                                  
                $mail->Subject = 'Verification';
                $mail->Body = $code;
            
                $mail->send();
            } catch (Exception $e) {
                echo 'Gửi thất bại: ', $mail->ErrorInfo;
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sendcode"])) {
            $email = $_POST["email"];

            $users = new Users();
            $listUser = $users->getUserByEmail($email);
            
            if (count($listUser)>0) {
                $randomcode = randomCode(); 
                $_SESSION['code'] = $randomcode;
                $_SESSION['userid'] = $listUser[0]["userid"];
                sendCodeEmail($email, $randomcode);
                echo '<script>alert("Mã đã được gửi đến email: ' .$email. '");</script>';
            } else {
                echo '<script>alert("Không tìm thấy email");</script>';
            }
        }  

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["checkcode"])) {
            $code1=$_POST["code"];
            $code=$_SESSION['code'];
            $userid=$_SESSION['userid'];

            if ($code1==$code) {
                header("Location: updatepass.php?userid=$userid");
                exit();
            } else {
                echo '<script>alert("Mã code sai");</script>';
            }
        }
    ?>

    <!-- Menu -->
    <?php include '../menu.php';?>

    <!-- Main -->
    <div id="main">
        <div class="box-sendcode">
            <h1>Lấy lại mật khẩu</h1>

            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <label for="email">Email:</label>
                <input type="email" name="email" required value="<?php echo isset($email) ? $email : ""?>">
                <br>
                <input type="submit" name="sendcode" id="sendcode" value="Gửi mã khôi phục">
            </form>

            <br><br>
            <form action="forgotpass.php" method="post">
                <label for="">Nhập code:</label>
                <input type="text" name="code" required>
                <br>
                <input type="submit" name="checkcode" id="check code" value="Tiếp tục"> 
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../footer.php';?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>