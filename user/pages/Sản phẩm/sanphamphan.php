<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user/pages/css/sanphamphan.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user//assets/css/footer.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user//assets/css/cart.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user//assets/css/menu.css">
    <link rel="stylesheet" href="/ĐACS2_NEW/user//assets/css/contact.css">
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

    <!-- Menu -->
    <?php include '../../menu.php'; ?>

    <!-- Main -->
    <div id="main"> 
        <!-- Sản phẩm -->
        <div id="sanpham">
            <div class="box-sanpham">
                <h1>Sản phẩm</h1>
                <img src="/ĐACS2_NEW/user/assets/img/Gioithieu.png" alt="">
                <?php 
                    $limit=12;
                    $page=isset($_GET["page"]) ? $_GET["page"] : 1;
                    $start=($page-1)*$limit;

                    if (!empty($_GET["search"])) {
                        $search=$_GET["search"];
                        $sql="SELECT * FROM sanphams WHERE namesp LIKE :search LIMIT :start, :limit";
                        $stmt=$conn->prepare($sql);
                        $stmt->bindValue(':search', '%' .$search. '%');
                    } else if (isset($_GET["type"])) {
                        $type=$_GET["type"];
                        $sql="SELECT * FROM sanphams WHERE type=:type LIMIT :start, :limit";
                        $stmt=$conn->prepare($sql);
                        $stmt->bindParam(':type', $type);
                    } else {
                        $sql="SELECT * FROM sanphams LIMIT :start, :limit";
                        $stmt=$conn->prepare($sql);
                    }
                    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
                    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                    $stmt->execute();
                    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

                    $count=0;
                    foreach ($result as $row) {
                        if ($count%3==0) {
                            echo '<div class="row-sanpham">';
                        }
                        echo '<div class="sanpham">';
                            echo '<div class="img">';
                                echo '<a href="/ĐACS2_NEW/user/handle/infosanpham.php?idsp=' .$row["idsp"]. '">';
                                    $image=$row["imagesp"];
                                    $imageinfo=getimagesizefromstring($image);
                                    $mime=$imageinfo['mime'];
                                    $imagesrc='data:' .$mime. ';base64,' .base64_encode($image);
                                    echo '<img src="' .$imagesrc. '"/>';
                                echo '</a>';
                            echo '</div>';
                            echo '<div class="name">';
                                echo '<h3>';
                                    echo '<a href="/ĐACS2_NEW/user/handle/infosanpham.php?idsp=' .$row["idsp"]. '">' .$row["namesp"]. '</a>';
                                echo '</h3>';
                            echo '</div>';
                            echo '<div class="des">' .$row["dessp"]. '</div>';
                            echo '<div class="price">' .$row["price"]. '</div>';
                            echo '<div class="link">';
                                echo '<a href="/ĐACS2_NEW/user/handle/infosanpham.php?idsp=' .$row["idsp"]. '">Xem chi tiết</a>';
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
                    $sql="SELECT * FROM sanphams";
                    $stmt=$conn->prepare($sql);
                    $stmt->execute();
                    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

                    for ($i=1;$i<=ceil(count($result)/$limit);$i++) {
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