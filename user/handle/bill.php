<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/assets/css/bill.css">
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

    <?php 
        if (isset($_POST["delivery"])) {
            $delivery=$_POST["delivery"];
        } else {
            $delivery="Chưa giao";
        }
        if (isset($_POST["status"])) {
            $status=$_POST["status"];
        } else {
            $status="Chưa thanh toán";
        }

        $sql="SELECT * FROM bill WHERE userid=:userid and delivery=:delivery and status=:status";
        $stmt=$conn->prepare($sql);
        $stmt->bindParam(':delivery', $delivery);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!-- Main -->
    <div id="main">
        <!-- Menu -->
        <?php include '../menu.php';?>

        <!-- Bill -->
        <div id="bill">
            <div class="box-bill">
                <?php if (isset($userid)) {?>
                    <h1><?php echo $_COOKIE["fullname"];?></h1>
                    <h3>Đơn hàng của bạn</h3>
                    <div class="choose">
                        <form action="bill.php" method="post">
                            <select name="delivery" id="" onchange="this.form.submit()">
                                <option value="Chưa giao" <?php if ($delivery=="Chưa giao") echo 'selected';?>>Chưa giao</option>
                                <option value="Đã giao" <?php if ($delivery=="Đã giao") echo 'selected';?>>Đã giao</option>
                            </select>
                            <select name="status" id="" onchange="this.form.submit()">
                                <option value="Chưa thanh toán" <?php if ($status=="Chưa thanh toán") echo 'selected';?>>Chưa thanh toán</option>
                                <option value="Đã thanh toán" <?php if ($status=="Đã thanh toán") echo 'selected';?>>Đã thanh toán</option>
                            </select>
                        </form>
                    </div>
                    <?php if (count($result)>0) {?>
                        <table class="show-bill">
                            <tr>
                                <th>ID đơn hàng</th>
                                <th>Họ và tên</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Địa chỉ</th>
                                <th>Phương thức thanh toán</th>
                                <th>Tổng tiền</th>
                                <th>Sản phẩm</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Thời gian</th>
                            </tr>
                            <?php 
                                foreach ($result as $row) {
                                    if ($row["typepay"]=="Thanh toán trực tiếp" || $row["status"]=="Đã thanh toán") {
                                        echo '<tr>';
                                        echo '<td>' .$row["idbill"]. '</td>';
                                        echo '<td>' .$row["fullname"]. '</td>';
                                        echo '<td>' .$row["phone"]. '</td>';
                                        echo '<td>' .$row["email"]. '</td>';
                                        echo '<td>' .$row["address"]. '</td>';
                                        echo '<td>' .$row["typepay"]. '</td>';
                                        echo '<td>' .$row["totalbill"]. '</td>';
                                        echo '<td>';
                                        echo '<table class="sub-table">';
                                        $str = $row["sanphams"];
                                        $numbers = explode(", ", $str);
                                        $count = count($numbers);
                                        foreach ($numbers as $index => $number) {
                                            if ($index == $count - 1) {
                                                continue;
                                            }
                                            $parts = explode("-", $number);
                                            $idsp = $parts[0];
                                            $countsp = $parts[1];
                                            $sql1="SELECT  * FROM sanphams WHERE idsp=:idsp";
                                            $stmt1=$conn->prepare($sql1);
                                            $stmt1->bindParam(':idsp', $idsp);
                                            $stmt1->execute();
                                            $result1=$stmt1->fetch(PDO::FETCH_ASSOC);
                                            echo '<tr>';
                                            echo '<td>' .$result1["namesp"]. '</td>';
                                            echo '<td>' .$countsp. '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</table>';
                                        echo '</td>';
                                        echo '<td>' .$row["status"]. '</td>';
                                        echo '<td>' .$row["note"]. '</td>';
                                        echo '<td>' .$row["time"]. '</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </table>
                        <br>
                        <h3 class="contact">Liên hệ ngay để hủy đơn hàng</h3>
                    <?php } else {
                            echo '<h4>Bạn chưa có đơn hàng nào</h4>';
                        } 
                    ?>
                <?php } else {
                        echo '<h1>Bạn chưa đăng nhập</h1>';
                    }
                ?>
            </div>
        </div>

        <!-- Cart -->
        <?php include '../cart.php'; ?>

        <!-- Footer -->
        <?php include '../footer.php';?>

        <!-- Contact -->
        <?php include '../contact.php';?>
    </div>

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