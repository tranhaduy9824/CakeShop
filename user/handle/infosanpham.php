<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/infosanpham.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/footer.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/cart.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/menu.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head>
<body>
    <?php 
        $server="localhost";
        $user="root";
        $pass="";
        $db="dacs2";

        if (isset($_COOKIE["userid"])) {
            $userid=$_COOKIE["userid"];
        }

        if (isset($_GET["idsp"])) {
            $idsp=$_GET["idsp"];
        }

        try {
            $conn=new PDO("mysql:host=$server;dbname=$db", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    <?php include '../menu.php' ?>

    <!-- Main -->
    <div id="main">
        <?php 
            $sql="SELECT * FROM sanphams WHERE idsp=:idsp";
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(':idsp', $idsp);
            $stmt->execute();
            $result=$stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo '<div class="box-infosp">';
                echo '<div class="imgsp">';
                $image=$result["imagesp"];
                $imageinfo=getimagesizefromstring($image);
                $mime=$imageinfo['mime'];
                $imagesrc='data:' .$mime. ';base64,' .base64_encode($image);
                echo '<img src="' .$imagesrc. '">';
                echo '</div>';
                echo '<div class="infosp">';
                echo '<h1>' .$result["namesp"]. '</h1>
                <p>Mã sản phẩm: ' .$result["idsp"]. '</p>
                <form action="infosanpham.php" method="post">
                <input type="hidden" name="idsp" value="' .$idsp. '">
                    <div class="add-cart">
                        <div class="price">
                            <p>Giá bán</p>
                            ' .$result["price"]. '
                        </div>
                        <div class="count">
                            <p>Số lượng:</p>
                            <input type="number" name="count" value="1" min="1" max="10">
                        </div>
                        <button type="submit" name="addcart"><i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng</button>
                    </div>
                </form>';
                echo '</div>';
                echo '</div>';
            }
        ?>
    </div>

    <?php 
        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["addcart"])) {
            if (isset($_COOKIE["userid"])) {
                if ($_COOKIE["status"]==0) {
                    $idsp=$_POST["idsp"];
                    $sql="SELECT * FROM sanphams WHERE idsp=:idsp";
                    $stmt=$conn->prepare($sql);
                    $stmt->bindParam(':idsp', $idsp);
                    $stmt->execute();
                    $result=$stmt->fetch(PDO::FETCH_ASSOC);

                    $userid=$_COOKIE["userid"];
                    $fullname=$_COOKIE["fullname"];
                    $imagesp=$result["imagesp"];
                    $namesp=$result["namesp"];
                    $count=$_POST["count"];
                    $price=floatval(str_replace(",", "", $result["price"]));
                    $total=number_format($price * $count, 0, ",", ",") . " VND";

                    $sql2="INSERT INTO carts (userid, idsp, fullname, imagesp, namesp, number, total) VALUES (:userid, :idsp, :fullname, :imagesp, :namesp, :number, :total)";
                    $stmt2=$conn->prepare($sql2);
                    $stmt2->bindParam(':userid', $userid);
                    $stmt2->bindParam(':idsp', $idsp);
                    $stmt2->bindParam(':fullname', $fullname);
                    $stmt2->bindParam(':imagesp', $imagesp);
                    $stmt2->bindParam(':namesp', $namesp);
                    $stmt2->bindParam(':number', $count);
                    $stmt2->bindParam(':total', $total);
                    $stmt2->execute();
                    echo '<script>alert("Thêm giỏ hàng thành công"); window.location.href = "/ĐACS2_NEW/user/index.php";</script>';
                } else {
                    echo '<script>alert("Tài khoản của bạn đã bị chặn"); window.location.href = "/ĐACS2_NEW/user/index.php";</script>';
                }
            }
            else {
                echo '<script>alert("Vui lòng đăng nhập"); window.location.href = "/ĐACS2_NEW/user/loginregister.php";</script>';
            }
        }
    ?>

    <!-- Cart -->
    <?php include '../cart.php';?>

    <!-- Footer -->
    <?php include '../footer.php';?>

    <!-- Contact -->
    <?php include '../contact.php';?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/ĐACS2_NEW/user/contact.js"></script>
    <script>
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