<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/pay.css">
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

        if (isset($_GET["idbill"])) {
            $idbill=$_GET["idbill"];
        }

        if (isset($_GET["typepay"]) && $_GET["typepay"]==="Thanh toán trực tuyến") {
            $carts = new Carts();
            $carts->deleteCartsByUserId($userid);
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
    <?php include '../menu.php';?>

    <!-- Main -->
    <div id="main">
        <?php 
            $bill = new Bill();
            $listBill = $bill->getBillByIdBill($idbill); 
        ?>
        <div class="box-main">
            <div class="box-qr">
                <h4>Quét mã để thanh toán đơn hàng ID: <span><?php echo $idbill;?></span></h4>
                <h4>Tổng số tiền: <span><?php echo $listBill["totalbill"];?></span></h4>
                <h4>Trần Hà Duy</h4>
                <p>1903 9068 4830 12</p>
                <img src="/CuoiKiWeb/user/assets/img/qrpay.jpg" alt="">
                <h5>Nội dung chuyển khoản (<span>*</span>): Họ-và-tên Id-đơn-hàng (Ví dụ: Tran Ha Duy 01)</h5>
            </div>
        </div>
    </div>

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
    </script>

    <?php
        // $apiEndpoint = 'https://my.sepay.vn/userapi/transactions/list/';
        // $apiKey = 'SHOUIL7TVRMMQXWXCLB9EJQDYEUFNMNFNJFATIPIR7BW3Q8V1KXZSDOUCZZZI9OG';

        // $start_date = '2023-01-01';
        // $end_date = '2023-12-31';
        // $status = 'completed';

        // $requestUrl = $apiEndpoint . '?api_key=' . $apiKey . '&start_date=' . $start_date . '&end_date=' . $end_date . '&status=' . $status;

        // $response = file_get_contents($requestUrl);

        // if ($response) {
        //     $responseData = json_decode($response, true);
        //     echo '<script>alert("Lấy dữ liệu thành công");</script>';
        // } else {
        //     echo 'Error: No response from API.';
        // }
    ?>  
</body>
</html>