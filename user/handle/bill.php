<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/bill.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/menu.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/footer.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/cart.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/contact.css">
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

        if (isset($_COOKIE["userid"])) {
            $userid=$_COOKIE["userid"];
        }

        $opendetailcart=0;
        if (isset($userid)) {
            $carts = new Carts();
            $listCart = $carts->getCartByUserId($userid);
            
            if (count($listCart)>0) {
                $opendetailcart=count($listCart);
            }
        }
    ?>

    <!-- Main -->
    <div id="main">
        <!-- Menu -->
        <?php include '../menu.php';?>

        <?php 
            if (isset($_POST["delivery"])) {
                $delivery = $_POST["delivery"];
            } else {
                $delivery = "";
            }
            if (isset($_POST["status"])) {
                $status = $_POST["status"];
            } else {
                $status = "";
            }

            if (empty($delivery) && empty($status)) {
                $sql = "SELECT * FROM bill WHERE userid=:userid";
                $stmt = $conn->prepare($sql);
            } else {
                $sql = "SELECT * FROM bill WHERE userid=:userid";
                if (!empty($delivery)) {
                    $sql .= " AND delivery = :delivery";
                }
                if (!empty($status)) {
                    $sql .= " AND status = :status";
                }
                $stmt = $conn->prepare($sql);
                if (!empty($delivery)) {
                    $stmt->bindParam(':delivery', $delivery);
                }
                if (!empty($status)) {
                    $stmt->bindParam(':status', $status);
                }
            }
            $stmt->bindParam(':userid', $userid);
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <!-- Bill -->
        <div id="bill">
            <div class="box-bill">
                <?php if (isset($userid)) {?>
                    <h1><?php echo $_COOKIE["fullname"];?></h1>
                    <h3>Đơn hàng của bạn</h3>
                    <div class="choose">
                        <form action="bill.php" method="post">
                            <label for="">Trạng thái</label>
                            <select name="status" id="" onchange="this.form.submit()">
                                <option value="Chưa thanh toán" <?php if ($status=="Chưa thanh toán" && $delivery!="Đã giao") echo 'selected'; if ($delivery=="Đã giao") echo 'disabled';?>>Chưa thanh toán</option>
                                <option value="Đã thanh toán" <?php if ($status=="Đã thanh toán" && $delivery!="Đã giao") echo 'selected'; if ($delivery=="Đã giao") echo 'disabled';?>>Đã thanh toán</option>
                                <option value="" <?php if ($status==""||$delivery=="Đã giao") echo 'selected';?>>Tất cả</option>
                            </select>
                            <label for="">Giao hàng</label>
                            <select name="delivery" id="" onchange="this.form.submit()">
                                <option value="Chưa giao" <?php if ($delivery=="Chưa giao") echo 'selected';?>>Chưa giao</option>
                                <option value="Đã giao" <?php if ($delivery=="Đã giao") echo 'selected';?>>Đã giao</option>
                                <option value="" <?php if ($delivery=="") echo 'selected';?>>Tất cả</option>
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
                                <th>Giao hàng</th>
                                <th>Thời gian</th>
                            </tr>
                            <?php 
                                foreach ($result as $row) {
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
                                        $sanphams = new Sanphams();
                                        $listSanpham = $sanphams->getSanphamsById($idsp);
                                        echo '<tr>';
                                        echo '<td>' .$listSanpham["namesp"]. '</td>';
                                        echo '<td>' .$countsp. '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</table>';
                                    echo '</td>';
                                    echo '<td>' .$row["status"]. '</td>';
                                    echo '<td>' .$row["note"]. '</td>';
                                    echo '<td>' .$row["delivery"]. '</td>';
                                    echo '<td>' .$row["time"]. '</td>';
                                    if ($row["typepay"]==="Thanh toán trực tuyến" && $row["status"]==="Chưa thanh toán") {
                                        echo '<td class="paybill"><a href="pay.php?idbill=' .$row["idbill"]. '">Thanh toán</a></td>';
                                    }
                                    echo '</tr>';   
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
    <script src="/CuoiKiWeb/user/contact.js"></script>
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