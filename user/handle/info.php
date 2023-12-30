<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/info.css">
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

        if (isset($_COOKIE["userid"])&&isset($_COOKIE["username"])&&isset($_COOKIE["fullname"])&&isset($_COOKIE["email"])&&isset($_COOKIE["phone"])) {
            $userid = $_COOKIE["userid"];
            $username = $_COOKIE["username"];
            $fullname = $_COOKIE["fullname"];
            $email = $_COOKIE["email"];
            $phone = $_COOKIE["phone"];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['avt'])) {
            $avt=file_get_contents($_FILES['avt']['tmp_name']);

            $users = new Users();
            $users->changeAvt($userid, $avt);

            setcookie('avt', $avt, time() + 86400, '/');

            header("Location: info.php");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['update-info'])) {
            $fullname1=$_POST["new-fullname"];
            $email1=$_POST["new-email"];
            $phone1=$_POST["new-phone"];

            $users = new Users();
            $users->changeInfo($userid, $email1, $phone1);

            setcookie('email', $email1, time() + 86400, '/');
            setcookie('phone', $phone1, time() + 86400, '/');

            echo '<script>alert("Cập nhập thành công"); window.location.href = "info.php";</script>';
        }

        if (isset($_GET["delete-cart"])) {
            $idcart=$_GET["delete-cart"];

            $carts = new Carts();
            $carts->deleteCartById($idcart);
        }

        if ($_SERVER["REQUEST_METHOD"]="POST" && isset($_POST["add-bill"])) {
            if (isset($_COOKIE["status"]) && $_COOKIE["status"]==0) {
                $address=$_POST["address"];
                $typepay=$_POST["type-pay"];
                $totalbill=$_POST["totalbill"];
                $status="Chưa thanh toán";
                $note=$_POST["note"];
                $sanphams="";
                $delivery="Chưa giao";
                
                $carts = new Carts();
                $listCart = $carts->getCartByUserId($userid);
                foreach ($listCart as $row) {
                    $sanphams.=$row["idsp"]."-".$row["number"].", ";
                }

                $bill = new Bill();
                $bill->setBill($userid, $fullname, $phone, $email, $address, $typepay, $totalbill, $sanphams, $status, $note, $delivery);

                if ($typepay==="Thanh toán trực tiếp") {
                    echo '<script>window.location.href="/CuoiKiWeb/user/handle/info.php?typepay=' .$typepay. '"</script>';
                } else {
                    $bill = new Bill();
                    $listBill = $bill->getBillByUserId($userid);
                    header("Location: pay.php?idbill=" . $listBill["idbill"]. "&typepay=" .$typepay);
                    exit();
                }
            } else {
                echo '<script>alert("Tài khoản của bạn đã bị chặn, không thể đặt hàng")</script>';
            }
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

    <!-- Menu -->
    <?php include '../menu.php' ?>

    <!-- Main -->
    <div id="main">
        <h1>Thông tin cá nhân</h1>
        <div class="box-info">
            <div class="box-left">
                <div class="box-avt">
                    <?php 
                        $users = new Users();
                        $listUser = $users->getUserByUserId($userid);
                        if (empty($listUser["avt"])) {
                            echo '<img src="/CuoiKiWeb/user/assets/img/avtmacdinh.jpg" alt="">';
                        }
                        else {
                            $avt = $listUser["avt"];
                            $infoavt = getimagesizefromstring($avt);
                            if (!empty($infoavt['mime'])) {
                                $mime = $infoavt['mime'];
                            } else $mime="";
                            $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                            echo '<img src="' .$avtsrc. '" alt="">';
                        }
                    ?>
                </div>
                <div class="form-update-avt">
                    <form action="info.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="avt" id="avt" onchange="this.form.submit()" required hidden>
                        <label name="update-avt" for="avt">Chọn tệp</label>
                    </form>    
                </div> 
            </div>
            <div class="box-right">
                <form action="info.php" method="post">
                <label for="">Họ và tên</label>
                    <br>
                    <input type="text" disabled value="<?php echo $fullname ?>">
                    <br>
                    <label for="">Email</label>
                    <br>
                    <input type="email" name="new-email" value="<?php echo $email ?>">
                    <br>
                    <label for="">Số điện thoại</label>
                    <br>
                    <input type="text" name="new-phone" value="<?php echo $phone ?>">
                    <br>
                    <input type="submit" name="update-info" value="Cập nhật">
                </form>
            </div>
        </div>
    </div>

    <!-- Detail-cart -->
    <div id="detail-cart">
        <hr>
        <h1>Giỏ hàng của bạn</h1>
        <div class="btn-change-detail">
            <div class="btn-detail">
                <div class="number detail change">1</div>
                <p>Giỏ hàng</p>
            </div>
            <div class="btn-pay">
                <div class="number pay">2</div>
                <p>Thông tin đơn hàng</p>
            </div>
            <div class="btn-completed">
                <div class="number complete">3</div>
                <p>Hoàn tất</p>
            </div>
        </div>

        <div class="box-detail">
            <?php 
                if ($_COOKIE["userid"]) {
                    $userid=$_COOKIE["userid"];

                    $carts = new Carts();
                    $listCart = $carts->getCartByUserId($userid);
                    $totalcart=0;

                    if (count($listCart)>0) {
                        echo '<table>';
                        echo '<tr class="title"><th colspan="2">Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Tổng cộng</th><th>Xóa</th>';
                        foreach ($listCart as $row) {
                            $sanphams = new Sanphams();
                            $listSanpham = $sanphams->getSanphamsById($row["idsp"]);

                            echo '<tr>';
                            $image=$row["imagesp"];
                            $imageinfo=getimagesizefromstring($image);
                            $mime=$imageinfo['mime'];
                            $imagesrc='data:' .$mime. ';base64,' .base64_encode($image);
                            echo '<td><img src="' .$imagesrc. '"></td>';
                            echo '<td>' .$row["namesp"]. '</td>'; 
                            echo '<td>' .$listSanpham["price"]. '</td>'; 
                            echo '<td>' .$row["number"]. '</td>'; 
                            echo '<td>' .$row["total"]. '</td>'; 
                            echo '<td><a href="info.php?delete-cart=' .$row["idcart"]. '"><i class="fas fa-times"></i></td>'; 
                            echo '</tr>';
                            $total=floatval(str_replace(",", "", $row["total"]));
                            $totalcart+=$total;
                        }
                        echo '</table>';
                    }
                    $totalcart=number_format($totalcart, 0, ",", ",") . " VND";
                    echo '<div class="box-bill">
                        <table>
                            <tr>
                                <td>Thành tiền</td>
                                <td>' .$totalcart. '</td>
                            </tr>
                            <tr>
                                <td>Giảm giá</td>
                                <td>-0 VND</td>
                            </tr>
                            <tr>
                                <td>Tổng đơn hàng</td>
                                <td>' .$totalcart. '</td>
                            </tr>
                        </table>
                        <div class="btn-pay">Tiến hành đặt hàng</div>
                    </div>';
                }
            ?>
        </div>

        <form action="info.php" method="post">
            <div class="box-pay">
                <div class="box-info-user">
                    <h2>Thông tin khách hàng</h2>
                    <table>
                        <tr>
                            <td>Họ và tên (<span>*</span>)</td>
                            <td><?php echo $fullname;?></td>
                        </tr>
                        <tr>
                            <td>Điện thoại (<span>*</span>)</td>
                            <td><?php echo $phone;?></td>
                        </tr>
                        <tr>
                            <td>Email (<span>*</span>)</td>
                            <td><?php echo $email;?></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ (<span>*</span>)</td>
                            <td>
                                <input type="text" name="address" required placeholder="Địa chỉ cụ thể">
                            </td>
                        </tr>
                        <tr>
                            <td>Ghi chú</td>
                            <td>
                                <input type="text" name="note" placeholder="Những điều cần thiết">
                            </td>
                        </tr>
                    </table>
                    <br>
                    <p style="font-size: 18px;">(<span style="color: red;">*</span>) Nội dung bắt buộc nhập</p>
                    <div class="btn-detail">Quay lại</div>
                </div>
                <div class="box-info-cart">
                    <h2>Thông tin đơn hàng</h2>
                    <?php 
                        $carts = new Carts();
                        $listCart = $carts->getCartByUserId($userid);
                        $totalcart=0;

                        if (count($listCart)>0) {
                            echo '<table>';
                            echo '<tr><th colspan="2">Sản phẩm</th><th>SL</th><th>Tổng cộng</th></tr>';
                            foreach ($listCart as $row) {
                                echo '<tr>';
                                $image=$row["imagesp"];
                                $imageinfo=getimagesizefromstring($image);
                                $mime=$imageinfo['mime'];
                                $imagesrc='data:' .$mime.';base64,' .base64_encode($image);
                                echo '<td><img src="' .$imagesrc. '"></td>';
                                echo '<td>' .$row["namesp"]. '</td>';
                                echo '<td>' .$row["number"]. '</td>';
                                echo '<td>' .$row["total"]. '</td>';
                                echo '</tr>';
                                $total=floatval(str_replace(",", "", $row["total"]));
                                $totalcart+=$total;
                            }
                            echo '</table>';
                        } 
                        $totalbill=$totalcart+30000;
                        $totalcart=number_format($totalcart, 0, ",", ",") . " VND";
                        $totalbill=number_format($totalbill, 0, ",", ",") . " VND";
                        echo '<input type="hidden" name="totalbill" value="' .$totalbill. '">';
                        echo '<div class="total-bill">
                            <div class="title-bill">
                                <p>Thành tiền</p>
                                <p>Giảm giá</p>
                                <p>Phí vận chuyển</p>
                                <p>Tổng đơn hàng</p>
                            </div>
                            <div class="price-bill">
                                <p>' .$totalcart. '</p>
                                <p>-0 VND</p>
                                <p>+30,000 VND</p>
                                <p class="totalbill">' .$totalbill. '</p>
                            </div>
                        </div>';
                    ?>
                    <h2>Phương thức thanh toán</h2>
                    <input type="radio" id="online-pay" name="type-pay" value="Thanh toán trực tuyến" checked>
                    <label for="online-pay">Thanh toán qua tài khoản ngân hàng</label>
                    <br>
                    <input type="radio" id="offline-pay" name="type-pay" value="Thanh toán trực tiếp">
                    <label for="offline-pay">Thanh toán trực tiếp khi nhận hàng</label>
                    <br>
                    <input type="submit" class="btn-complete" name="add-bill" value="Hoàn tất hóa đơn">
                </div>
            </div>
        </form>

        <div class="box-complete">
            <?php 
                if (isset($_GET["typepay"]) && $_GET["typepay"]==="Thanh toán trực tiếp") {
                    echo "<script>
                        document.querySelector('.box-detail').classList.add('close');
                        var numbers = document.querySelectorAll('.number');
                        numbers.forEach(function(number) {
                            number.classList.add('change');
                        });
                    </script>";
                    echo '<h1>Đặt hàng thành công</h1>
                    <p>Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi</p>
                    <p><b>Thời gian xử lí đơn hàng: Từ 06h00 đến 21h00 hằng ngày</b></p>
                    <p>Nhân viên chúng tôi sẽ sớm liên hệ với Quý khách trong thời gian sớm nhất để xác nhận đơn hàng và vận chuyển</p>
                    <p><b>Lưu ý:</b></p>
                    <p>
                    Tổng giá trị đơn hàng chưa bao gồm phí vận chuyển. Nhân viên bán hàng sẽ liên hệ và báo phí giao hàng.
                    Nếu Quý khách hàng có thắc mắc, xin vui lòng liên hệ với chúng tôi qua số hotline <span>0867125575</span>.
                    Khi đơn hàng đã được xác nhận và xuất kho, một số yêu cầu hủy đơn hàng sẽ không thực hiện được.
                    Xin chân thành cảm ơn quý khách hàng !
                    </p>';
                    $carts = new Carts();
                    $carts->deleteCartsByUserId($userid);
                }
            ?>
        </div> 
    </div>
    <?php 
        if ($opendetailcart>0) {
            echo '<script>document.querySelector(\'#detail-cart\').classList.add(\'open\');</script>';
        }
    ?>

    <!-- Cart -->
    <?php include '../cart.php';?>

    <!-- Footer -->
    <?php include '../footer.php'?>

    <!-- Contact -->
    <?php include '../contact.php'?>

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
        var boxdetail=document.querySelector('.box-detail');
        var boxpay=document.querySelector('.box-pay');
        var numberpay=document.querySelector('.number.pay');

        for (const btndetail of btndetails) {
            btndetail.addEventListener('click', function() {
                window.location.href="/CuoiKiWeb/user/handle/info.php"
            });
            btndetail.addEventListener('click', function() {
                numberpay.classList.remove('change'); 
            });
        };
        for (const btnpay of btnpays) {
            btnpay.addEventListener('click', function() {
                boxdetail.classList.add('close');
            });
            btnpay.addEventListener('click', function() {
                boxpay.classList.add('open');
            });
            btnpay.addEventListener('click', function() {
                numberpay.classList.add('change');
            });
        };

        var urlParams = new URLSearchParams(window.location.search);
        var numberpay2 = urlParams.get('numberpay');
        if (numberpay2!==null) {
            boxdetail.classList.add('close');
            boxpay.classList.add('open');
            numberpay.classList.add('change');
        };
    </script>
</body>
</html>