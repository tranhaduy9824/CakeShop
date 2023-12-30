<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/pages/css/sanphamphan.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user//assets/css/footer.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user//assets/css/cart.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user//assets/css/menu.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user//assets/css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
</head>
<body>
    <?php 
        require_once '../../classes/connectMySql.php';
        require_once '../../classes/users.php';
        require_once '../../classes/carts.php';
        require_once '../../classes/comments.php';
        require_once '../../classes/sanphams.php';
        require_once '../../classes/bill.php';
        require_once '../../classes/messages.php';

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

    <!-- Menu -->
    <?php include '../../menu.php'; ?>

    <!-- Main -->
    <div id="main"> 
        <!-- Sản phẩm -->
        <div id="sanpham">
            <div class="box-sanpham">
                <h1>Sản phẩm</h1>
                <img src="/CuoiKiWeb/user/assets/img/Gioithieu.png" alt="">
                <?php 
                    $limit=12;
                    $page=isset($_GET["page"]) ? $_GET["page"] : 1;
                    $start=($page-1)*$limit;

                    $sanphams = new Sanphams();
                    $listSanpham = $sanphams->searchSanpham($start, $limit);

                    $count=0;
                    foreach ($listSanpham as $row) {
                        if ($count%3==0) {
                            echo '<div class="row-sanpham">';
                        }
                        echo '<div class="sanpham">';
                            echo '<div class="img">';
                                echo '<a href="/CuoiKiWeb/user/handle/infosanpham.php?idsp=' .$row["idsp"]. '">';
                                    $image=$row["imagesp"];
                                    $imageinfo=getimagesizefromstring($image);
                                    $mime=$imageinfo['mime'];
                                    $imagesrc='data:' .$mime. ';base64,' .base64_encode($image);
                                    echo '<img src="' .$imagesrc. '"/>';
                                echo '</a>';
                            echo '</div>';
                            echo '<div class="name">';
                                echo '<h3>';
                                    echo '<a href="/CuoiKiWeb/user/handle/infosanpham.php?idsp=' .$row["idsp"]. '">' .$row["namesp"]. '</a>';
                                echo '</h3>';
                            echo '</div>';
                            echo '<div class="des">' .$row["dessp"]. '</div>';
                            echo '<div class="price">' .$row["price"]. '</div>';
                            echo '<div class="link">';
                                echo '<a href="/CuoiKiWeb/user/handle/infosanpham.php?idsp=' .$row["idsp"]. '">Xem chi tiết</a>';
                            echo '</div>';
                            echo '<div class="ribbon ' .$row["ribbon"]. '">' .$row["ribbon"]. '</div>';
                        echo '</div>';
                        $count++;
                        if ($count%3==0) {
                            echo '</div>';
                        }
                    }
                ?>
            </div>
            <div class="btn-pagesp">
                <?php 
                    $listSanpham = $sanphams->getSanphams();

                    for ($i=1;$i<=ceil(count($listSanpham)/$limit);$i++) {
                        echo '<div class="number-page"><a href="sanphamphan.php?page=' . $i . '">' .$i. '</a></div>';
                    }
                ?>
            </div>
        </div>
    </div>

    <!-- Cart -->
    <?php include '../../cart.php';?>

    <!-- Footer -->
    <?php include '../../footer.php';?>

    <!-- Contact -->
    <?php include '../../contact.php';?>
    
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