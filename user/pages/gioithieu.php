<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/gioithieu.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/menu.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/footer.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/cart.css">
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

    <!-- Main -->
    <div id="main">
        <!-- Menu -->
        <?php include '../menu.php'; ?>

        <!-- Giới thiệu -->
        <div id="intro">
            <div class="box-intro">
                <div class="img-intro">
                    <img src="/ĐACS2_NEW/user/assets/img/Giới thiệu/a1.png" alt="">
                </div>
                <div class="content-intro">
                    <h1>Giới thiệu</h1>
                    <img src="/ĐACS2_NEW/user/assets/img/Gioithieu.png" alt="">
                    <p>
                        Được biết đến như một kiểu mẫu ẩm thực Pháp, những chiếc bánh của chúng tôi không chỉ gói trọn nét duyên dáng & tinh tế của ẩm thực phương Tây mà còn là “biểu tượng” của lòng quan tâm, sự chăm sóc dịu dàng Bạn muốn gửi trao.
                    </p>
                    <img src="/ĐACS2_NEW/user/assets/img/Giới thiệu/a2.jpg" alt="">
                    <p>
                        Đặt tiêu chí “Chất lượng” là ưu tiên hàng đầu, chúng tôi không ngừng cải tiến, phát triển và hoàn thiện bằng những hành động rõ ràng và cụ thể như – Cơ sở vật chất khang trang, nhà xưởng hiện đại và đạt tiêu chuẩn qua những chứng nhận có giá trị ISO – HACCP. Và trên hết, là sự công nhận tin yêu ngày càng lớn của Bạn đối với chúng tôi.
                    </p>
                    <br>
                    <p>
                        Với thông điệp Gửi Trọn Yêu Thương, chúng tôi mong muốn cùng Bạn mang đến bao điều ngọt ngào trìu mến cho những người thân yêu, gia đình & bè bạn. Còn gì ý nghĩa hơn khi Bạn không chỉ thưởng thức những chiếc bánh tươi ngon, mà còn là tận hưởng những khoảnh khắc sẻ chia hạnh phúc.
                    </p>
                    <br>
                    <p>
                        Trong buổi ăn tối quây quần đầm ấm, trong bữa tiệc sum họp vui vầy hay trong ngày sinh nhật lung linh ánh nến… những chiếc bánh sẽ mãi là món quà tặng lấp lánh yêu thương.
                    </p>
                    <br>
                    <div>
                        <img src="/ĐACS2_NEW/user/assets/img/Giới thiệu/a3.jpg" alt="">
                        <img src="/ĐACS2_NEW/user/assets/img/Giới thiệu/a4.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart -->
    <?php include '../cart.php'; ?>

    <!-- Footer -->
    <?php include '../footer.php'?>

    <!-- Contact -->
    <?php include '../contact.php'?>

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