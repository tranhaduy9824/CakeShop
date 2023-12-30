<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/menu.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/footer.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/cart.css">
    <link rel="stylesheet" href="/CuoiKiWeb/user/assets/css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="./assets/themify-icons/themify-icons.css">
    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"
    />
</head> 
<body>
    <?php 
        require_once 'classes/connectMySql.php';
        require_once 'classes/users.php';
        require_once 'classes/carts.php';
        require_once 'classes/comments.php';
        require_once 'classes/sanphams.php';
        require_once 'classes/bill.php';
        require_once 'classes/users.php';

        if (isset($_COOKIE["userid"])){
            $userid=$_COOKIE["userid"];
        }

        if (isset($_COOKIE["avt"])) {
            $avt=$_COOKIE["avt"];
        }

        if ($_SERVER["REQUEST_METHOD"]="POST" && isset($_POST["add-comment"])) {
            if (isset($_COOKIE["userid"])) {
                if ($_COOKIE["status"]==0) {
                    $content=$_POST['comment'];

                    $comments = new Comments();
                    $comments->setComment($userid, $content);

                    header("Location: index.php");
                    exit();
                } else {
                    echo '<script>alert("Tài khoản của bạn đã bị chặn");</script>';
                }
            }
            else {
                echo '<script>alert("Vui lòng đăng nhập"); window.location.href = "loginregister.php";</script>';
            }
        }

        if (isset($_GET['delete-comment'])) {
            $idcomment=$_GET['delete-comment'];

            $comments = new Comments();
            $comments->deleteCommentbyId($idcomment);
        }

        $opendetailcart=0;
        if  (isset($userid)) {
            $carts = new Carts();
            $listCart = $carts->getCartByUserId($userid);
            
            if (count($listCart)>0) {
                $opendetailcart=count($listCart);
            }
        }
    ?>

    <!-- Menu -->
    <?php include 'menu.php'; ?>

    <!-- Main -->
    <div id="main">
        <!-- Sản phẩm mới -->
        <div id="newsp">
            <h2 class="title">Sản phẩm mới</h2>
            <img src="./assets/img/Gioithieu.png" alt="">
            <div class="sliders-newsp">
                <div class="slider">
                    <div class="item">
                        <a href="">Flan</a>
                        <div class="price">
                            12,000 VND
                        </div>
                        <div class="link">
                            <a href="">Xem chi tiết</a>
                        </div>
                    </div>
                    <div class="img-item">
                        <img src="./assets/img/Home/Sản phẩm mới/flan.jpg" alt="">
                    </div>
                </div>
                <div class="slider">
                    <div class="item">
                        <a href="">Bánh mì que cấp đông</a>
                        <div class="price">
                            49,000 VND
                        </div>
                        <div class="link">
                            <a href="">Xem chi tiết</a>
                        </div>
                    </div>
                    <div class="img-item">
                        <img src="./assets/img/Home/Sản phẩm mới/banhmique.jpg" alt="">
                    </div>
                </div>
                <div class="slider">
                    <div class="item">
                        <a href="">Su kem</a>
                        <div class="price">
                            33,000 VND
                        </div>
                        <div class="link">
                            <a href="">Xem chi tiết</a>
                        </div>
                    </div>
                    <div class="img-item">
                        <img src="./assets/img/Home/Sản phẩm mới/sukem.jpg" alt="">
                    </div>
                </div>
                <div class="slider">
                    <div class="item">
                        <a href="">Kem chuối dừa</a>
                        <div class="price">
                            15,000 VND
                        </div>
                        <div class="link">
                            <a href="">Xem chi tiết</a>
                        </div>
                    </div>
                    <div class="img-item">
                        <img src="./assets/img/Home/Sản phẩm mới/kemchuoidua.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sản phẩm hot + mới-->
        <div id="hotnew">
            <div class="box-hotnew">
                <div class="menu-new-hot">
                    <div id="btnhot" class="change">
                        Sản phẩm hot
                    </div>
                    <div id="btnnew">
                        Sản phẩm mới
                    </div>
                </div>
                <div class="sliders-hot">
                    <?php 
                        $ribbon='hot';
                        $sanphams = new Sanphams();
                        $listSanpham = $sanphams->getSanphamByRibbon($ribbon);

                        foreach ($listSanpham as $row) {
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
                        }
                    ?>
                </div>
                <div class="sliders-new">
                    <?php 
                        $ribbon='new';
                        $sanphams = new Sanphams();
                        $listSanpham = $sanphams->getSanphamByRibbon($ribbon);

                        foreach ($listSanpham as $row) {
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
                        }
                    ?>
                </div>
            </div>
        </div>

        <!-- PromoNews -->
        <div id="promonews">
            <div class="box-promonews">
                <div class="news">
                    <h2>Tin khuyến mãi</h2>
                    <img src="./assets/img/Gioithieu.png" alt=""> <br>
                    <div class="img">
                        <a href="">
                            <img src="./assets/img/Home/Tin tức khuyến mãi/news.jpg" alt="">
                        </a>
                    </div>
                    <div class="name">
                        <h3>
                            <a href="">Ưu đãi mừng khai trương</a>
                        </h3>
                    </div>
                    <div class="des">
                        <p>
                            Vui mừng ra mắt cửa hàng với nhiều ưu đãi hấp dẫn trong suốt tuần lễ khai trương từ 20/10 đến hết ngày 30/10/2023.
                        </p>
                    </div>
                </div>
                <div class="mooncake">
                    <h2>Bánh trung thu 2023</h2>
                    <img src="./assets/img/Gioithieu.png" alt="">
                    <div class="img">
                        <a href="">
                            <img src="./assets/img/Home/Tin tức khuyến mãi/moon.jpg" alt="">
                        </a>
                    </div>
                    <div class="name">
                        <h3>
                            <a href="">Bảng giá bánh trung thu 2023</a>
                        </h3>
                    </div>
                    <div class="des">
                        <p>

                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Review -->
        <div id="review">
            <div class="box-review">
                <div class="title">
                    <h2>Cảm nhận khách hàng</h2>
                    <img src="./assets/img/Gioithieu.png" alt="">
                </div>
                <div class="sliders-review">
                    <div class="avt-review">
                        <div class="img">
                            <img src="./assets/img/Home/Avtreview/a1.jpg" alt="">
                        </div>
                        <div class="img">
                            <img src="./assets/img/Home/Avtreview/a2.jpg" alt="">
                        </div>
                        <div class="img">
                            <img src="./assets/img/Home/Avtreview/a3.jpg" alt="">
                        </div>
                        <div class="img">
                            <img src="./assets/img/Home/Avtreview/a4.jpg" alt="">
                        </div>
                        <div class="img">
                            <img src="./assets/img/Home/Avtreview/a5.jpg" alt="">
                        </div>
                        <div class="img">
                            <img src="./assets/img/Home/Avtreview/a6.png" alt="">
                        </div>
                    </div>
                    <div class="item-review">
                        <div class="item-container">
                            <div class="name">
                                Uyen Nguyen
                            </div>
                            <div class="content">
                                Là fan cứng của cửa hàng. Thích bánh ở đây, vị thanh nhẹ, không quá ngọt. Đúng gu ^^... Đặc biệt là bánh su, bánh bắp, bánh matcha và bánh chà bông heo cay nữa
                            </div>
                        </div>
                        <div class="item-container">
                            <div class="name">
                                Mỹ Thuận
                            </div>
                            <div class="content">
                                Tôi rất thích bánh Smoke sausage hôm nào cũng ghé cửa hàng mua 1 cái dành cho bữa sáng. Ngoài ra, còn rất nhiều loại bánh nướng khác mình có thể thay đổi khẩu vị để không bị ngán.
                            </div>
                        </div>
                        <div class="item-container">
                            <div class="name">
                                Linh Truc
                            </div>
                            <div class="content">
                                BÁNH KEM BẮP VÀ MATCHA Ở ĐÂY NGON XUẤT SẮC LUÔN CÒN BÁNH SU KEM THẦN THÁNH THÌ KHỎI BÀN LUÔN , SẼ ỦNG HỘ DÀI DÀI HEHEE
                            </div>
                        </div>
                        <div class="item-container">
                            <div class="name">
                                Sea Nguyen
                            </div>
                            <div class="content">
                                Mình thích bánh kem bắp của shop , vị kem không quá ngọt , bông lan xốp mềm
                            </div>
                        </div>
                        <div class="item-container">
                            <div class="name">
                                Bich Ngoc
                            </div>
                            <div class="content">
                                Bánh kem ngon, béo, hình ngộ nghĩnh đáng yêu. Sẽ luôn ủng hộ!!!
                            </div>
                        </div>
                        <div class="item-container">
                            <div class="name">
                                Quỳnh Uyên
                            </div>
                            <div class="content">
                                Là fan trung thành của shop. Bánh ngon, vị nhẹ thanh, không quá ngọt, rất hợp gu ^^
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-comment">
                <div class="send-comment">
                    <?php 
                        if (isset($userid)) {
                            $users = new Users();
                            $user = $users->getUserByUserId($userid);

                            echo '<div class="avt-comment">';
                            if (empty($user["avt"])) {
                                echo '<img src="./assets/img/avtmacdinh.jpg" alt="">'; 
                            } else {
                                $avt=$user["avt"];
                                $infoavt = getimagesizefromstring($avt);
                                if (!empty($infoavt['mime'])) {
                                    $mime = $infoavt['mime'];
                                } else $mime="";
                                $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                echo '<img src="' .$avtsrc. '" alt="">';
                            }
                            echo '</div>';
                        }
                    ?>
                    <div class="box-addcomment">
                        <form action="index.php" method="post">
                            <input type="text" name="comment" placeholder="Bình luận" required>
                            <input type="submit" name="add-comment" value="Gửi">
                        </form>
                    </div>
                </div>
                <div class="list-comment">
                    <?php 
                        $comments = new Comments();
                        $listComment = $comments->getComments();

                        if (count($listComment)>0) {
                            echo '<table>';
                            foreach ($listComment as $row) {
                                $userid2=$row["userid"];
                                $users = new Users();
                                $listUser = $users->getUserByUserId($userid2);
                                echo '<tr>';
                                if (empty($listUser["avt"])) {
                                    echo '<td rowspan="2"><img src="./assets/img/avtmacdinh.jpg" alt=""></td>'; 
                                } 
                                else {
                                    $avt=$listUser["avt"];
                                    $infoavt = getimagesizefromstring($avt);
                                    if (!empty($infoavt['mime'])) {
                                        $mime = $infoavt['mime'];
                                    } else $mime="";
                                    $avtsrc='data:' .$mime. ';base64,' .base64_encode($avt);
                                    echo '<td rowspan="2"><img src="' .$avtsrc. '" alt=""></td>'; 
                                }
                                echo '<td class="name-comment">' .$listUser["fullname"]. '</td>'; 
                                echo '<td rowspan="2" class="time-comment">' .$row["updated_at"]. '</td>'; 
                                if (isset($userid)) {
                                    if ($userid==$userid2) {
                                        echo '<td rowspan="2" class="btn-delete-comment"><a href="index.php?delete-comment=' .$row["idcomment"]. '"><i class="fas fa-times"></i></a></td>'; 
                                    }
                                    else {
                                        echo '<td rowspan="2"></td>';
                                    }
                                }
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td>' .$row["content"]. '</td>'; 
                                echo '</tr>';
                            }
                            echo '</table>';
                        } else {
                            echo '<p>Hiện tại chưa có bình luận nào</p>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart -->
    <?php include 'cart.php';?>

    <!-- Footer -->
    <?php include 'footer.php'?>

    <!-- Contact -->
    <?php include 'contact.php'?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script
      type="text/javascript"
      src="https://code.jquery.com/jquery-1.11.0.min.js"
    ></script>
    <script
      type="text/javascript"
      src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"
    ></script>
    <script
      type="text/javascript"
      src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"
    ></script>
    <script src="/CuoiKiWeb/user/contact.js"></script>
    <script src="./main.js"></script>
</body>
</html>